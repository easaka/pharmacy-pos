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

    $cat = Category::create(['name'=>'Analgesics','description'=>'Pain killers']);
    $sup = \App\Models\Supplier::create(['name'=>'Pharma Supplier','phone'=>'024xxxxxxx']);
    Product::create([
        'sku'=>'PARA-500',
        'name'=>'Paracetamol 500mg',
        'category_id'=>$cat->id,
        'supplier_id'=>$sup->id,
        'cost_price'=>10,
        'selling_price'=>15,
        'reorder_level'=>10
    ]);
    }
}
