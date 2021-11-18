<?php

namespace TF;

use TF\Contracts\RestLoggerInterface;
use Illuminate\Support\Facades\Redis;

class RedisLogger extends  AbstractLogger implements RestLoggerInterface
{
    /**
     * return all models
     */
    public function getLogs()
    {
        $lines =  Redis::get('restlogs');
        $lines = json_decode($lines);
        return collect($lines);
    }

    /**
     * save logs in redis
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
     * delete all logs
     */
    public function deleteLogs()
    {
        Redis::del('restlogs');
    }
}
