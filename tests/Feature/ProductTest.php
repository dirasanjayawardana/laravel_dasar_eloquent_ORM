<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Relations\OneToMany\Product;
use Database\Seeders\CategorySeeder;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\ImageSeeder;
use Database\Seeders\ProductSeeder;
use Tests\TestCase;

class ProductTest extends TestCase
{
    // untuk relasi one to many bisa menggunakan method hasMany() pada model, untuk relasi bidirectional (dua arah) menggunakan method belongsTo() pada model yg lain
    public function testOneToMany()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $product = Product::find("1");
        self::assertNotNull($product);

        // $category = Category::where("id", $product->category_id)->get();
        $category = $product->category;
        self::assertNotNull($category);
        self::assertEquals("FOOD", $category->id);
    }


    // HasOneOfMany (mengambil satu data saja dari relasi one to many)
    // sebenarnya bisa menggunakan query builder, $model1->model2()->where("kondisi")-->get()
    // namun untuk mempermudah bisa dengan menambahkan method di model yang mengembalikan HasOne
    public function testHasOneOfMany()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $category = Category::find("FOOD");
        self::assertNotNull($category);

        $cheapestProduct = $category->cheapestProduct;
        // select * from `products` where `products`.`category_id` = ? and `products`.`category_id` is not null order by `price` asc limit 1
        self::assertNotNull($cheapestProduct);
        self::assertEquals("1", $cheapestProduct->id);

        // select * from `products` where `products`.`category_id` = ? and `products`.`category_id` is not null order by `price` desc limit 1
        $mostExpensiveProduct = $category->mostExpensiveProduct;
        self::assertNotNull($mostExpensiveProduct);
        self::assertEquals("2", $mostExpensiveProduct->id);
    }


    // Polymorphic Relationships (relasi antar tabel namun relasinya bisa berbeda model)
    // namun relasi ini tidak dianjurkan karena dalam relation database, satu kolom foreign key hanya bisa mnegacu ke satu tabel
    // OneToOne Polymorphic mirip seperti relasi OneToOne, hanya saja relasinya bisa lebih dari satu model
    // contoh Customer dan Product punya satu Image, artinya Model Image berelasi OneToOne dengan Customer dan Product
    public function testOneToOnePolymorphic()
    {
        $this->seed([CustomerSeeder::class, CategorySeeder::class, ProductSeeder::class, ImageSeeder::class]);

        $customer = Product::find("DIRA");
        self::assertNotNull($customer);

        $image = $customer->image;
        self::assertNotNull($image);

        self::assertEquals("https://www.dirapp.com/image/2.jpg", $image->url);
    }
}
