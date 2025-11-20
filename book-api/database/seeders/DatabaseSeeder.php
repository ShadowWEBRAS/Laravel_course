<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::factory()->create([
            'name' => 'Admin',
            'surname' => 'User',
            'email' => 'admin@library.ru',
            'password' => Hash::make('Admin123'),
            'role' => 'admin',
        ]);

        // Regular reader
        User::factory()->create([
            'name' => 'Regular',
            'surname' => 'User',
            'email' => 'user@library.ru',
            'password' => Hash::make('password'),
            'role' => 'reader',
        ]);

        // Create more test users
        User::factory()->count(10)->create([
            'role' => 'reader',
        ]);

        // Create books
        Book::factory()->count(50)->create();
    }
}
