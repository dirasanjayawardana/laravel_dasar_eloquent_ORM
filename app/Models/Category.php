<?php

namespace App\Models;

use App\Models\Relations\ManyThrough\Review;
use App\Models\Relations\OneToMany\Product;
use App\Models\Scopes\IsActiveScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Category extends Model
{
    // Model --> Representasi dari Table di database
    // atribute di Model, bisa dioverride untuk menambahkan informasi tentang schema table
    // $table, $primaryKey, $keyType, $incrementing, $timestamp dll
    // secara default eloquent terdapat kolom created_at dan updated_at, jika tidak butuh bisa mengoverride $timestamp menjadi false
    protected $table = "categories";
    protected $primaryKey = "id";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = false;


    // secara default, semua atribute di Model tidak bisa di set langsung secara masal menggunakan method create()
    // agar bisa di set langsung secara masal dengan create() maka attribute di model harus di daftarkan di $fillable
    protected $fillable = [
        "id",
        "name",
        "description"
    ];


    // untuk relasi one to many bisa menggunakan method hasMany() pada model, untuk relasi bidirectional (dua arah) menggunakan method belongsTo() pada model yg lain
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, "category_id", "id");
    }


    // Query Scope (cara menambahkan kondisi query secara otomatis, agar setiap melakukan query akan mengikuti kondisi yang telah ditentukan)
    // Query Global Scope --> kondisi ditambahkan di model, secara otomatis ketika melakukan query, kondisi yang ditambahkan akan diterapkan di query builder, contoh ketika menggunakan trait SoftDelete otomatis menambahkan kondisi "where deleted_at = null"
    // contoh menambahkan kondisi Active dan Non Active, dimana setiap melakukan query akan selalu mengambil data yg Active saja di kolom is_active
    // php artisan make:scope NamaScope --> membuat scope di app/Models/Scopes, lalu tambahkan kondisi scope yg sudah dibuat, lalu tambahkan scope ke Model dengan mengoverride booted() dan menggunakan method addGlobalScope(scope)
    protected static function booted(): void
    {
        parent::booted();
        self::addGlobalScope(new IsActiveScope());
    }


    // HasOneOfMany (mengambil satu data saja dari relasi one to many)
    // sebenarnya bisa menggunakan query builder, $model1->model2()->where("kondisi")-->get()
    // namun untuk mempermudah bisa dengan menambahkan method di model yang mengembalikan HasOne
    public function cheapestProduct(): HasOne
    {
        return $this->hasOne(Product::class, "category_id", "id")->oldest("price");
    }
    public function mostExpensiveProduct(): HasOne
    {
        return $this->hasOne(Product::class, "category_id", "id")->latest("price");
    }


    // relasi HasManyThrough (relasi antar model yang tidak berhubungan langsung, tetapi melewati model lain)
    // contoh relasi model Category oneToMany ke Proudct, model Product oneToMany ke Review, maka bisa membuat relasi antara Category dengan Review yang melewati model Product
    public function reviews(): HasManyThrough
    {
        return $this->hasManyThrough(
            Review::class,
            Product::class,
            "category_id", // foreign key di products
            "product_id", // foreign key di reviews
            "id", // primary key di categories
            "id" // primary key di products
        );
    }
}
