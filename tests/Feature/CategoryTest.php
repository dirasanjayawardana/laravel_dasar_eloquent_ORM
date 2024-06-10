<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Relations\OneToMany\Product;
use App\Models\Scopes\IsActiveScope;
use Database\Seeders\CategorySeeder;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\ReviewSeeder;
use Tests\TestCase;

// override setUp method di TestCase
class CategoryTest extends TestCase
{
    // INSERT
    // save() --> returnnya adalah boolean, true jika berhasil, false jika gagal
    public function testInsert()
    {
        $category = new Category();

        $category->id = "GADGET";
        $category->name = "Gadget";

        $result = $category->save();

        self::assertTrue($result);
    }


    // Query Builder
    // untuk menggunakan Query Builder di Model, cukup gunakan static method query() di model yg sudah dibuat, tidak perlu menggunakan DB::table("namaTable") lagi
    // karena di Model ada magic method __call dan __callStatic, maka bisa langsung menggunakan method Query Builder tanpa menggunakan method query()
    // ClassModel::query()->method(); atau ClassModel::mehtod();
    public function testInsertMany()
    {
        // untuk insert banyak data bisa gunakan Query Builder dengan data yang ditampung dalam array
        $categories = [];
        for ($i = 0; $i < 10; $i++) {
            $categories[] = [
                "id" => "ID $i",
                "name" => "Name $i"
            ];
        }

        // $result = Category::query()->insert($categories);
        $result = Category::insert($categories); // tanpa menggunakan method query(), karena menggunakan magic method
        // insert into `categories` (`id`, `name`) values (?, ?), (?, ?), (?, ?), (?, ?), (?, ?), (?, ?), (?, ?)
        self::assertTrue($result);

        // $total = Category::query()->count();
        $total = Category::count(); // tanpa menggunakan method query(), karena menggunakan magic method
        // select count(*) as aggregate from `categories`
        self::assertEquals(10, $total);
    }


    // FIND (untuk mendapatkan satu data menggunakan primary key)
    // find(primaryKey)
    public function testFind()
    {
        $this->seed(CategorySeeder::class);

        // $category = Category::query()->find("FOOD");
        $category = Category::find("FOOD");
        // select * from `categories` where `categories`.`id` = ? limit 1

        self::assertNotNull($category);
        self::assertEquals("FOOD", $category->id);
        self::assertEquals("Food", $category->name);
        self::assertEquals("Food Category", $category->description);
    }


    // UPDATE
    // bisa menggunakan update() atau save(), harus melakukan find() terlebih dahulu, bukan new Object
    // jika terpaksa harus new Object, maka atribute $exist harus diubah menjadi true
    public function testUpdate()
    {
        $this->seed(CategorySeeder::class);

        $category = Category::find("FOOD");
        // select * from `categories` where `categories`.`id` = ? limit 1
        $category->name = "Food Updated";

        $result = $category->update();
        // update `categories` set `name` = ? where `id` = ?
        self::assertTrue($result);
    }


    // SELECT (melakukan select data lebih dati satu, kembaliannya berupa collection object Model, sehingga bisa digunakan untuk update())
    public function testSelect()
    {
        for ($i = 0; $i < 5; $i++) {
            $category = new Category();
            $category->id = "ID $i";
            $category->name = "Name $i";
            $category->save();
        } // insert into `categories` (`id`, `name`) values (?, ?)

        $categories = Category::whereNull("description")->get();
        // select * from `categories` where `description` is null
        self::assertEquals(5, $categories->count());

        $categories->each(function ($category) {
            self::assertNull($category->description);

            $category->description = "Updated";
            $category->update();
            //  update `categories` set `description` = ? where `id` = ?
        });
    }


    // UPDATE MANY (melakukan update untuk banyak data)
    public function testUpdateMany()
    {
        $categories = [];
        for ($i = 0; $i < 10; $i++) {
            $categories[] = [
                "id" => "ID $i",
                "name" => "Name $i"
            ];
        }

        $result = Category::insert($categories);
        self::assertTrue($result);

        Category::whereNull("description")->update([
            "description" => "Updated"
        ]); // update `categories` set `description` = ? where `description` is null

        $total = Category::where("description", "=", "Updated")->count();
        // select count(*) as aggregate from `categories` where `description` = ?
        self::assertEquals(10, $total);
    }


    // DELETE
    // menggunakan delete(), harus melakukan find() terlebih dahulu, bukan new Object
    // jika terpaksa harus new Object, maka atribute $exist harus diubah menjadi true
    public function testDelete()
    {
        $this->seed(CategorySeeder::class);

        $category = Category::find("FOOD");
        $result = $category->delete();
        // delete from `categories` where `id` = ?
        self::assertTrue($result);

        $total = Category::count();
        // select count(*) as aggregate from `categories`
        self::assertEquals(0, $total);
    }


    // DELETE MANY
    public function testDeleteMany()
    {
        $categories = [];
        for ($i = 0; $i < 10; $i++) {
            $categories[] = [
                "id" => "ID $i",
                "name" => "Name $i"
            ];
        }

        $result = Category::insert($categories);
        self::assertTrue($result);

        $total = Category::count();
        self::assertEquals(10, $total);

        Category::whereNull("description")->delete();

        $total = Category::count();
        self::assertEquals(0, $total);
    }


    // CREATE
    // create(attributes) --> membuat model otomatis seusai dengan data arraynya, tidak perlu set key value satu satu
    // secara default, semua atribute di Model tidak bisa di set langsung secara masal menggunakan method create()
    // agar bisa di set langsung secara masal dengan create() maka attribute di model harus di daftarkan di $fillable
    public function testCreate()
    {
        $request = [
            "id" => "FOOD",
            "name" => "Food",
            "description" => "Food Category"
        ];

        $category = new Category($request);
        $category->save();

        self::assertNotNull($category->id);
    }
    // create() --> langsung menyimpan ke database tanpa harus menggunakan method save()
    public function testCreateUsingQueryBuilder()
    {
        $request = [
            "id" => "FOOD",
            "name" => "Food",
            "description" => "Food Category"
        ];

        // $category = Category::query()->create($request);
        $category = Category::create($request);

        self::assertNotNull($category->id);
    }


    // UPDATE --> fill() --> melakukan update object model secara sekaligus
    public function testUpdateMass()
    {
        $this->seed(CategorySeeder::class);

        $request = [
            "name" => "Food Updated",
            "description" => "Food Category Updated"
        ];

        $category = Category::find("FOOD");
        $category->fill($request);
        $category->save();

        self::assertNotNull($category->id);
    }


    // Query Scope (cara menambahkan kondisi query secara otomatis, agar setiap melakukan query akan mengikuti kondisi yang telah ditentukan)
    // Query Global Scope --> kondisi ditambahkan di model, secara otomatis ketika melakukan query, kondisi yang ditambahkan akan diterapkan di query builder, contoh ketika menggunakan trait SoftDelete otomatis menambahkan kondisi "where deleted_at = null"
    // contoh menambahkan kondisi Active dan Non Active, dimana setiap melakukan query akan selalu mengambil data yg Active saja di kolom is_active
    // php artisan make:scope NamaScope --> membuat scope di app/Models/Scopes, lalu tambahkan kondisi scope yg sudah dibuat, lalu tambahkan scope ke Model dengan mengoverride booted() dan menggunakan method addGlobalScope(scope)
    public function testGlobalScope()
    {
        $category = new Category();
        $category->id = "FOOD";
        $category->name = "Food";
        $category->description = "Food Category";
        $category->is_active = false;
        $category->save();

        // data tidak ditemukan karena is_active nya false
        $category = Category::find("FOOD");
        self::assertNull($category);

        // withoutGlobalScopes([NamaScope::class]) --> mematikan globalScope
        // mengambil semua data termasuk yang is_active nya false
        $category = Category::withoutGlobalScopes([IsActiveScope::class])->find("FOOD");
        self::assertNotNull($category);
    }


    // untuk relasi one to many bisa menggunakan method hasMany() pada model, untuk relasi bidirectional (dua arah) menggunakan method belongsTo() pada model yg lain
    public function testOneToMany()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $category = Category::find("FOOD");
        self::assertNotNull($category);

        // $products = Product::where("category_id", $category->id)->get();
        $products = $category->products;

        self::assertNotNull($products);
        self::assertCount(2, $products);
    }


    // ketika suatu model memiliki relasi ke model lain, maka bisa melakukan CRUD model lain dengan menggunakan method relationship
    public function testOneToManyQuery()
    {
        $category = new Category();
        $category->id = "FOOD";
        $category->name = "Food";
        $category->description = "Food Category";
        $category->is_active = true;
        $category->save();

        $product = new Product();
        $product->id = "1";
        $product->name = "Product 1";
        $product->description = "Description 1";

        $category->products()->save($product);

        self::assertNotNull($product->category_id);
    }


    // ketika suatu model memiliki relasi ke model lain, maka bisa melakukan CRUD model lain dengan menggunakan method relationship
    public function testRelationshipQuery()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $category = Category::find("FOOD");
        $products = $category->products;
        self::assertCount(2, $products);

        // select * from `products` where `products`.`category_id` = ? and `products`.`category_id` is not null and `stock` <= ?
        $outOfStockProducts = $category->products()->where('stock', '<=', 0)->get();
        self::assertCount(2, $outOfStockProducts);
    }


    // relasi HasManyThrough (relasi antar model yang tidak berhubungan langsung, tetapi melewati model lain)
    // contoh relasi model Category oneToMany ke Proudct, model Product oneToMany ke Review, maka bisa membuat relasi antara Category dengan Review yang melewati model Product
    public function testHasManyThrough()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class, CustomerSeeder::class, ReviewSeeder::class]);

        $category = Category::find("FOOD");
        self::assertNotNull($category);

        $reviews = $category->reviews;
        self::assertNotNull($reviews);
        self::assertCount(2, $reviews);
    }


    // Querying Relations
    // menggunakan Query seoerti di Query Builder pada method realtionship di Model
    public function testQueryingRelations()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $category = Category::find("FOOD");
        $products = $category->products()->where("price", "=", 200)->get();

        self::assertCount(1, $products);
        self::assertEquals("2", $products[0]->id);

    }


    // Agregating Relations
    // menggunakan agregate query pada method relationship di Model
    public function testAggregatingRelations()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $category = Category::find("FOOD");
        $total = $category->products()->count();

        self::assertEquals(2, $total);

        $total = $category->products()->where('price', 200)->count();

        self::assertEquals(1, $total);

    }
}
