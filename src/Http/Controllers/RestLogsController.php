<?php

namespace TF\Http\Controllers;

use App\Http\Controllers\Controller;
use TF\Contracts\RestLoggerInterface;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

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
        $restlogs = $this->paginate($restlogs, 2, $_GET['page']??"");
        $currentPage = $restlogs->currentPage();
        $total = $restlogs->total();
        $lastPage = $restlogs->lastPage();
        $page = $_GET['page'] ?? 0 + 1;

        if (count($restlogs) > 0) {
            $restlogs = $restlogs->sortByDesc('created_at');
        } else {
            $restlogs = [];
        }

        return view('restlog::index', compact('restlogs','currentPage','total','lastPage'));
    }

    public function paginate($items, $perPage, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
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
