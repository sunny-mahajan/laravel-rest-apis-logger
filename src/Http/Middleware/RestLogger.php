<?php

namespace TF\Http\Middleware;

use Closure;
use TF\Contracts\RestLoggerInterface;

class RestLogger
{
    protected $logger;

    /**
     * Constructor
     *
     * @param RestLoggerInterface $logger
     */
    public function __construct(RestLoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        return $response;
    }

    /**
     * Undocumented function
     *
     * @param [\Illuminate\Http\Request] $request
     * @param [\Illuminate\Http\Response] $response
     *
     * @return void
     */
    public function terminate($request, $response)
    {
        $this->logger->saveLogs($request, $response);
    }
}
