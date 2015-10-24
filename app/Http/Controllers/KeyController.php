<?php

namespace App\Http\Controllers;


use App\Http\Requests\Key\GetKeyProcessRequest;
use App\Http\Requests\Key\VerifyRequest;
use App\Repositories\Key\KeyRepositoryInterface;
use Laracasts\Flash\Flash;

class KeyController extends Controller
{

    protected $keyRepository;

    /**
     * KeyController constructor.
     */
    public function __construct(KeyRepositoryInterface $keyRepository)
    {
        $this->keyRepository = $keyRepository;
    }

    public function index()
    {
        return view('front.key.index');
    }

    public function verify(VerifyRequest $request)
    {
        $res = $this->keyRepository->verifyCode($request->code, $request->ip());
        if ($res){
            return redirect(getenv('REDIRECT_SUCCESS'));
        } else {
            Flash::error('Ваш ключ не прошел верификацию. Повторите попытку или отправте запрос на получение нового ключа!');
            return view('front.key.index');
        }
    }

    public function getKeyIndex(){
        return view('front.key.getKey');
    }

    public function getKeyProcess(GetKeyProcessRequest $request){
        $res = $this->keyRepository->registerPhone($request->phone);

        Flash::success('На ваш номер была отправлена SMS с кодом. Используйте его в форм ниже для продолжения!');
        return redirect('/');
    }




}
