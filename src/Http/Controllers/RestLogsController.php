<?php

namespace TF\Http\Controllers;

use App\Http\Controllers\Controller;
use TF\Contracts\RestLoggerInterface;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class RestLogsController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(RestLoggerInterface $logger, Request $request)
    {
        $input = $request->all();
        $restlogs = $logger->getLogs();
        $perPage = $input['s'] ?? 20;
        $offset = $perPage*($input['p']??'1');
        
        if(isset($input['m']) && $restlogs != null )
        {
            if(isset($input['r']) && $input['r'] == 'SUCCESS')
            {
                $restlogs = $restlogs->filter(function ($value, $key) use($input){
                    return $value->method == $input['m'] && $value->res_status < 400;
                });
            }
            elseif(isset($input['r']) && $input['r'] == 'FAIL')
            {
                $restlogs = $restlogs->filter(function ($value, $key) use($input){
                    return $value->method == $input['m'] && $value->res_status > 400;
                });
            }
            else{
                $restlogs = $restlogs->filter(function ($value, $key) use($input){
                    return $value->method == $input['m'];
                });
            }

        }
        elseif(!is_array($restlogs)){
            if(isset($input['r']) && $input['r'] == 'SUCCESS')
            {
                $restlogs = $restlogs->filter(function ($value, $key) use($input){
                    return $value->res_status < 400;
                });
            }
            elseif(isset($input['r']) && $input['r'] == 'FAIL')
            {
                $restlogs = $restlogs->filter(function ($value, $key) use($input){
                    return $value->res_status > 400;
                });
            }
        }
        if($restlogs != null)
        {
            $restlogs = $restlogs->sortByDesc('created_at');
            $restlogs = $this->paginate($restlogs, $perPage, $_GET['p']??"");
        }
        else
        {
            $restlogs = [];
        }
        return view('restlog::index', compact('restlogs','offset'));
    }

    /**
     * Pagination for restlogs.
     *
     * @param Array $items
     * @param Integer $perPage
     * @param Integer $page
     * @param Array $options
     * 
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
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
