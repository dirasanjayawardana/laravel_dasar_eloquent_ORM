<?php

namespace Tests\Feature;

use App\Models\Category;
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
        self::assertTrue($result);

        // $total = Category::query()->count();
        $total = Category::count(); // tanpa menggunakan method query(), karena menggunakan magic method
        self::assertEquals(10, $total);
    }
}
