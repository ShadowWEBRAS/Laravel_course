<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return response()->json([
        'message' => 'Authentication required',
        'api_endpoints' => [
            'register' => '/api/auth/register',
            'login' => '/api/auth/login'
        ]
    ], 401);
})->name('login');

Route::get('/', function () {
    return response()->json([
        'message' => 'Book API is running',
        'version' => '1.0',
        'endpoints' => [
            'public' => [
                'GET /api/books' => 'List all books',
                'GET /api/books/{id}' => 'Get book details',
                'POST /api/auth/register' => 'Register new user',
                'POST /api/auth/login' => 'Login user'
            ],
            'protected' => [
                'POST /api/books' => 'Create book (admin only)',
                'PUT /api/books/{id}' => 'Update book (admin only)',
                'DELETE /api/books/{id}' => 'Delete book (admin only)',
                'GET /api/rents' => 'List rents',
                'POST /api/rents' => 'Rent a book',
                'PUT /api/rents/{id}' => 'Return a book',
                'DELETE /api/rents/{id}' => 'Delete rent record'
            ]
        ]
    ]);
});
