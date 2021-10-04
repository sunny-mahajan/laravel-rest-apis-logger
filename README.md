# Rest APIs Logger

This is a small package that can helps in debugging with API logs.

##  Installation

1. Install the package via composer

```bash
composer require tfspl/restlogger
```
## Usage

1.  Laravel 5.5 and higher uses Package Auto-Discovery, so doesn't require you to manually add
the ServiceProvider. If you use a lower version of Laravel you must register it in your 
_app.php_ file:

```bash
TF\Providers\ApiLogServiceProvider::class
```

2. Publish the config file with:

```bash
php artisan vendor:publish --tag=config --provider="TF\Providers\ApiLogServiceProvider"
```

The config file is called *restlogs.php*. Currently supported drivers are *db* and *file*

By default the logger will use *file* to log the data. But if you want to use Database for logging, migrate table by using

You can also configure which fields should not be logged like passwords, secrets, etc.

***You dont need to migrate if you are just using file driver***

```bash
php artisan migrate
```

3. Add middleware named ***restlogger*** to the route or controller you want to log data

4. Dashboard can be accessible via ***yourdomain.com/restlogs***

## Clear the logs

You can permenently clear the logs by using the following command.
```bash
php artisan restlogs:clear
```
