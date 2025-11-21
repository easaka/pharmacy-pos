<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Category;
use App\Models\Product;


class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       // roles should be seeded earlier if using roles table
    $admin = User::create([
        'name'=>'Admin',
        'email'=>'admin@example.com',
        'password'=>Hash::make('password'),
        'role'=> 'admin'
    ]);

    // Seed permissions
    $this->call([
        \Database\Seeders\PermissionSeeder::class,
    ]);

    // Seed products with categories and suppliers
    $this->call([
        \Database\Seeders\ProductSeeder::class,
    ]);
    }
}
