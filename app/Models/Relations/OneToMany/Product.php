<?php

namespace App\Models\Relations\OneToMany;

use App\Models\Category;
use App\Models\Relations\ManyThrough\Review;
use App\Models\Relations\OneToOne\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $table = "products";
    protected $primaryKey = "id";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = false;


    // untuk relasi one to many bisa menggunakan method hasMany() pada model, untuk relasi bidirectional (dua arah) menggunakan method belongsTo() pada model yg lain
    // satu Category memiliki banyak relasi di Product, satu Product hanya ada satu relasi di Category
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, "category_id", "id");
    }


    // untuk relasi one to many bisa menggunakan method hasMany() pada model, untuk relasi bidirectional (dua arah) menggunakan method belongsTo() pada model yg lain
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, "product_id", "id");
    }


    // relasi ManyToMany harus membuat tabel jembatan sebagai tabel penengahnya (intermediate Table)
    // untuk membuat relasi manyToMany bisa menggunakan belongsToMany di kedua modelnya
    // contoh relasi manyToMany antara model Customer dan Product, tabel customers_likes_product sebagai jembatannya
    // pivot(); untuk mengambil semua isi kolom di intermediate table, secare default hanya foreign key model 1 dan 2 saja yang akan diquery di Pivot Attribute, jika ingin menambhakan kolom lain, bisa tambahkan pada relasi BelongsToMany dengan method withPivot(namaKolom)
    public function likedByCustomers(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class, "customers_likes_products", "product_id", "customer_id")
            ->withPivot("created_at");
    }
}
