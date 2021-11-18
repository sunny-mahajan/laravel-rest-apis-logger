<?php

namespace TF;

use TF\Contracts\RestLoggerInterface;
use Illuminate\Support\Facades\Redis;

class RedisLogger extends  AbstractLogger implements RestLoggerInterface
{
    /**
     * Read logs from redis and return
     *
     * @return Array
     */
    public function getLogs()
    {
        $logs =  Redis::get('restlogs');
        if($logs != null)
        {
            $logs = json_decode($logs);
            return collect($logs);
        }
        else{
            return [];
        }

    }

    /**
     * Write logs to redis
     *
     * @param [type] $request
     * @param [type] $response
     * @return void
     */
    public function saveLogs($request, $response)
    {
        $data = $this->logData($request, $response);
        $lines =  Redis::get('restlogs');
        $lines = json_decode($lines, true) ?? [];        
        array_push($lines, $data);
        Redis::set('restlogs', json_encode($lines));
    }

    /**
     * Deletes all logs from redis
     *
     * @return void
     */
    public function deleteLogs()
    {
        Redis::del('restlogs');
    }
}
