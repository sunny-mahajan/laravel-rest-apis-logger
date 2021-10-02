<?php

use Illuminate\Support\Facades\Route;

Route::get('/restlogs', 'TF\Http\Controllers\RestLogsController@index')->name("restlogs.index");

Route::delete('/restlogs/delete', 'TF\Http\Controllers\RestLogsController@delete')->name("restlogs.deletelogs");
