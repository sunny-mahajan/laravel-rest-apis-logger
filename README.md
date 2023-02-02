# Rest APIs Logger

This is a small package that can helps in debugging with API logs.

##  Installation

1. Install the package via composer

```bash
composer require tfspl/restlogger
```
## Usage

1. Publish the config file with:

```bash
php artisan vendor:publish --tag=config --provider="TF\Providers\RestLogsServiceProvider"
```

The config file is called *restlogs.php*. Currently supported drivers are *db*, *file* and *redis*.

By default the logger will use *file* to log the data. If you want to use redis for logging make sure 

that you have installed the laravel package *predis/predis*. But if you want to use Database for logging,

migrate table by using below command.

You can also configure which fields should not be logged like passwords, secrets, etc.

***You dont need to migrate if you are just using file driver***

```bash
php artisan migrate
```

2. Add middleware named ***restlogger*** to the route or controller you want to log data

3. Dashboard can be accessible via ***yourdomain.com/restlogs***

## Clear the logs

You can permenently clear the logs by using the following command.
```bash
php artisan restlogs:clear
```
