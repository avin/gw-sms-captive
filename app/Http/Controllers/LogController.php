<?php

namespace App\Http\Controllers;

use App\Repositories\InternetSession\InternetSessionRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

    public function index(Request $request)
    {
        if (preg_match('/([\d]{2}\/[\d]{2}\/[\d]{4}\ [\d]{2}:[\d]{2})\ -\ ([\d]{2}\/[\d]{2}\/[\d]{4}\ [\d]{2}:[\d]{2})/', $request->daterange, $matches)){
            $from = new Carbon($matches[1]);
            $to = new Carbon($matches[2]);
            $sessions = $this->internetSessionRepository->byDateRange($from, $to);
        } else {
            $sessions = $this->internetSessionRepository->all();
        }

        return view('front.log.index', compact('sessions'));
    }

    public function get($sessionId)
    {
        $pathToFile = storage_path('iptables_logs').DIRECTORY_SEPARATOR.$sessionId.'.log';
        return response()->download($pathToFile);
    }

}
