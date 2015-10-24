<?php
namespace App\Repositories\Key;

use App\Events\KeyWasCreated;
use App\Repositories\EloquentBaseRepository;
use App\Repositories\InternetSession\InternetSessionRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;


class EloquentKeyRepository extends EloquentBaseRepository implements KeyRepositoryInterface
{
    protected $key;
    protected $internetSessionRepository;

    public function __construct(Model $key, InternetSessionRepositoryInterface $internetSessionRepository)
    {
        parent::__construct($key);
        $this->key = $key;
        $this->internetSessionRepository = $internetSessionRepository;
    }

    protected function generateCode(){
        return str_random(6);
    }

    public function registerPhone($phone){
        if (! preg_match('/^[78]([\d]{10})$/', $phone, $matches)){
            return false;
        }
        $number = $matches[1];
        $data = [
            'phone_number' => $number,
            'key' => $this->generateCode(),
            'until' => Carbon::now()->addMinutes(10)
        ];
        $key = $this->key->create($data);
        Event::fire(new KeyWasCreated($key));
    }

    public function verifyCode($code, $ip){
        $key = $this->key->where('key', $code)->first();
        if ($key){
            if ($key->until < Carbon::now()){
                return false;
            }
            $this->internetSessionRepository->register($key->id, $ip);
            return true;
        }
        return false;
    }

}