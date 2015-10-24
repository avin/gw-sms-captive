<?php
namespace App\Repositories\InternetSession;

use App\Events\SessionStart;
use App\Repositories\EloquentBaseRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;


class EloquentInternetSessionRepository extends EloquentBaseRepository implements InternetSessionRepositoryInterface
{
    protected $internetSession;

    public function __construct(Model $internetSession)
    {
        parent::__construct($internetSession);
        $this->internetSession = $internetSession;
    }

    /**
     * Получить MAC по IP
     * @param $ip
     * @return string
     */
    protected function getMacByIp($ip)
    {
        $process = new Process("arp | grep '{$ip}\\s' | awk '{print $3}'");
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return $process->getOutput();
    }

    /**
     * Регистрация новой интернет-сесси
     * @param $keyId
     * @param $ip
     */
    public function register($keyId, $ip)
    {
        $mac = trim($this->getMacByIp($ip));
        $internetSession = $this->internetSession->create([
            'key_id' => $keyId,
            'mac' => $mac,
            'ip' => $ip,
            'active' => true,
            'until' => Carbon::now()->addMinutes(getenv('SESSION_TIME_IN_MINUTES')),
        ]);
        if ($internetSession){
            Event::fire(new SessionStart($internetSession));
        }
    }

    /**
     * Получить активные просроченные записи
     */
    public function getActiveOverdue(){
        return $this->internetSession->where('active', '=', true)->where('until', '<', Carbon::now())->get();
    }

    public function deactivate($internetSession){
        $internetSession->active = false;
        $internetSession->save();
    }

}