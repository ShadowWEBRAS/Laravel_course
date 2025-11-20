<?php

use App\Http\Controllers\MainController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Routing\Route as RoutingRoute;
use Symfony\Component\Routing\Router;

/* Route::get('/', function () {
    // dump('hello');
    // dump(config('database.connections.mysql'));
    dump(config('custom.custom_test', 'var not exist'));
    dump(config('custom.custom_test_env'));
    return view('welcome');
}); */

// ----Video 5----
Route::get('/', [MainController::class, 'index']);
// ---------------

// ----Video 3----

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

// ----Video 4----

Route::get('/v4', function () {
    return view('hi', ['title' => 'Main page']);
});

Route::view('/v4s', 'hi', ['title' => 'Main short page']);
/*
Route::get('/posts/{id}', function ($id) {
    return "Posts page, id: {$id}";
});

Route::get('/posts/{id?}', function ($id = 1) {
    return "Posts page?, id: {$id}";
});
*/

Route::get('/posts/{id}/comments/{comment_id}', function ($id, $comment_id) {
    return "Posts page, id: {$id}. Comment id: {$comment_id}";
});

/*
Route::get('/posts/{id}', function ($id) {
    return "Posts page, id: {$id}";
})->where(['id' => '[0-9]+']);
// for global pattern app/Provides/AppServiceProvider.php
*/

Route::get('/posts/{id}', function ($id) {
    return "Posts page, id: {$id}";
});

Route::get('/posts/{slug}', function ($slug) {
    return "Posts SLUG, id: {$slug}";
});

Route::get('/search/{search}', function ($search) {
    return "Search: {$search}";
})->where(['search' => '.*']);

Route::post('posts', function(){
    return 'Store post';
    // see bootstrap/app.php:15
});

Route::match(['get', 'post'], '/get-post', function() {
    return 'Hello from GET|POST';
})->withoutMiddleware(VerifyCsrfToken::class);

Route::any('/anyMethods', function() {
    return 'Hello from ANY';
})->withoutMiddleware(VerifyCsrfToken::class);

Route::redirect('/here', '/posts/777');

// ----Video 5----

/*
Route::get('/admin', function() {
    return "Admin main page";
});
Route::get('/admin/posts', function() {
    return "Admin posts page";
});
Route::get('/admin/posts/{id}', function($id) {
    return "Admin post {$id}";
});
*/

Route::prefix('admin')->group(function() {
    Route::get('/', function() {
        return "Admin main page";
    });
    Route::get('/posts', function() {
        return "Admin posts page";
    });
    Route::get('/posts/{id}', function($id) {
        return "Admin post {$id}";
    });
});

/* Route::fallback(function() {
    abort(404, '404 - Page not found!!!'); // see resources/views/errors/404.blade.php
    return response()->json(['answer' => '404 - Page not found'], 404);
    return response('404 - Page not found', 404);
}); */
