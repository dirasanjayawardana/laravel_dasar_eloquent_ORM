<?php

namespace Tests\Feature;

use App\Models\Category;
use Database\Seeders\CategorySeeder;
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
}
