<?php

use App\Category;
use App\Product;
use App\Transaction;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        User::truncate();
        Category::truncate();
        Product::truncate();
        Transaction::truncate();
        DB::table('category_product')->truncate();

        factory(User::class, 200)->create();
        factory(Category::class, 30)->create();
        factory(Product::class, 500)->create()->each(
            function($product){
                $categories = Category::all()->random(mt_rand(1, 5))->pluck('id');

                $product->categories()->attach($categories);
            }
        );
        factory(Transaction::class, 1000)->create();
    }
}
