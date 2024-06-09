<?php

namespace Database\Seeders;

use App\Models\Relations\OneToMany\Product;
use App\Models\Relations\Polymorphic\Tag;
use App\Models\Voucher;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tag = new Tag();
        $tag->id = "dira";
        $tag->name = "dira sanjaya wardana";
        $tag->save();

        $product = Product::find("1");
        $product->tags()->attach($tag);

        $voucher = Voucher::first();
        $voucher->tags()->attach($tag);
    }
}
