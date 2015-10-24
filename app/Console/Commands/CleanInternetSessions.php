<?php

namespace App\Console\Commands;


use App\Repositories\InternetSession\InternetSessionRepositoryInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class CleanInternetSessions extends Command
{

    protected $internetSessionRepository;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobs:cleanInternetSessions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean old InternetSessions';

    /**
     * Create a new command instance.
     *
     */
    public function __construct(InternetSessionRepositoryInterface $internetSessionRepository)
    {
        parent::__construct();
        $this->internetSessionRepository = $internetSessionRepository;
    }

    /**
     * Заполнение свойств
     * @param $elementProperties
     * @param $blankProperties
     */
    protected function cleanFirewallRules($ip, $mac)
    {
        $process = new Process("sudo /sbin/iptables -D internet -t mangle -m mac --mac-source {$mac} -j RETURN");
        $process->run();

        $process = new Process("sudo /usr/local/bin/rmtrack.sh {$ip}");
        $process->run();


        Log::info("Clean iptables rules for {$ip} {$mac}");
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $internetSessions = $this->internetSessionRepository->getActiveOverdue();
        foreach ($internetSessions as $internetSession) {
            $this->internetSessionRepository->deactivate($internetSession);

            $this->cleanFirewallRules($internetSession->ip, $internetSession->mac);
            Log::info("Close session {$internetSession->id} for MAC '{$internetSession->mac}' IP '{$internetSession->ip}'");
        }
    }

}
