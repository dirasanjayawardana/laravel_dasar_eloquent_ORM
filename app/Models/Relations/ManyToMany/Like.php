<?php

namespace App\Models\Relations\ManyToMany;
use App\Models\Relations\OneToMany\Product;
use App\Models\Relations\OneToOne\Customer;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Like extends Pivot
{
    // relasi ManyToMany harus membuat tabel jembatan sebagai tabel penengahnya (intermediate Table)
    // untuk membuat relasi manyToMany bisa menggunakan belongsToMany di kedua modelnya
    // contoh relasi manyToMany antara model Customer dan Product, tabel customers_likes_product sebagai jembatannya

    // Pivot Model (mirip seperti model biasa, namun turunan dari pivot, bisa query langsung lewat pivot model atau menambahkan relasi pada pivot model)
    // pada pivot class secara default $incrementing nya bernilai false, pivot class juga tidak mendukung softDeletes
    // untuk menonaktifkan updated_at dan created_at harus menggunakan usesTimestamps() yg mengembalikan false
    protected $table = "customers_likes_products";
    protected $foreignKey = "customer_id";
    protected $relatedKey = "product_id";
    public $timestamps = false;

    public function usesTimestamps(): bool
    {
        return false;
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, "customer_id", "id");
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, "product_id", "id");
    }
}
