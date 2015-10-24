<?php

namespace App\Http\Controllers;


use App\Http\Requests\Key\GetKeyProcessRequest;
use App\Http\Requests\Key\VerifyRequest;
use App\Repositories\InternetSession\InternetSessionRepositoryInterface;
use Laracasts\Flash\Flash;

class LogController extends Controller
{

    protected $internetSessionRepository;

    /**
     * KeyController constructor.
     */
    public function __construct(InternetSessionRepositoryInterface $internetSessionRepository)
    {
        $this->internetSessionRepository = $internetSessionRepository;
    }

    public function index()
    {
        $sessions = $this->internetSessionRepository->all();
        return view('front.log.index', compact('sessions'));
    }

    public function get($sessionId)
    {
        $pathToFile = storage_path('iptables_logs').DIRECTORY_SEPARATOR.$sessionId.'.log';
        return response()->download($pathToFile);
    }

}
