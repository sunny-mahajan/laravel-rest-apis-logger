<?php

namespace TF;

use TF\Contracts\RestLoggerInterface;

class DBLogger extends AbstractLogger implements RestLoggerInterface
{
    /**
     * Model for saving logs
     *
     * @var [type]
     */
    protected $logger;

    public function __construct(RestLog $logger)
    {
        parent::__construct();

        $this->logger = $logger;
    }

    /**
     * return all models
     */
    public function getLogs()
    {
        return $this->logger->all();
    }

    /**
     * save logs in database
     */
    public function saveLogs($request, $response)
    {
        $data = $this->logData($request, $response);

        $this->logger->fill($data);

        $this->logger->save();
    }

    /**
     * delete all logs
     */
    public function deleteLogs()
    {
        $this->logger->truncate();
    }
}
