<?php

namespace TF\Http\Controllers;

use App\Http\Controllers\Controller;
use TF\Contracts\RestLoggerInterface;

class RestLogsController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(RestLoggerInterface $logger)
    {
        $restlogs = $logger->getLogs();

        if (count($restlogs) > 0) {
            $restlogs = $restlogs->sortByDesc('created_at');
        } else {
            $restlogs = [];
        }

        return view('restlog::index', compact('restlogs'));
    }

    /**
     * Handles Deletion of Rest Logs
     *
     * @param RestLoggerInterface $logger
     *
     * @return void
     */
    public function delete(RestLoggerInterface $logger)
    {
        $logger->deleteLogs();

        return redirect()->back();
    }
}
