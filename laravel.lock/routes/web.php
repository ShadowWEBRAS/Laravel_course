<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // dump('hello');
    // dump(config('database.connections.mysql'));
    dump(config('custom.custom_test', 'var not exist'));
    dump(config('custom.custom_test_env'));
    return view('welcome');
});

Route::get('/v3', function () {
    // $cache = app()->make('cache');
    // $cache->put('test', 123);
    // dd($cache->get('test'));

    // $cache = app('cache');

    // $cache = cache();
    // $cache->put('test', 2232);
    // dd($cache->get('test'));

    dump(Cache::get('test'));

    dd(Illuminate\Support\Facades\App::make('cache'));
});
