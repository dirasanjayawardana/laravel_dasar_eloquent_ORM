<?php

namespace App\Models\Relations\OneToOne;

use App\Models\Relations\ManyThrough\Review;
use App\Models\Relations\OneThrough\VirtualAccount;
use App\Models\Relations\OneToMany\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Facades\Date;

class Customer extends Model
{
    // untuk relasi one to one bisa menggunakan method hasOne() pada model, untuk relasi bidirectional (dua arah) menggunakan method belongsTo() pada model yg lain
    protected $table = "customers";
    protected $primaryKey = "id";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = false;


    // Relasi one to one antara id dengan customer_id, dengan model wallet
    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class, "customer_id", "id");
    }


    // relasi HasOneThrough (relasi antar model yang tidak berhubungan langsung, tetapi melewati model lain)
    // contoh relasi model Customer oneToOne ke Wallet, model Wallet oneToOne VirtualAccount, maka bisa membuat relasi antara Customer dengan VirtualAccount yang melewati model Wallet
    public function virtualAccount(): HasOneThrough
    {
        return $this->hasOneThrough(
            VirtualAccount::class,
            Wallet::class,
            "customer_id", // foreign key di wallets tabel
            "wallet_id", // foreign key di virtual_accounts tabel
            "id", // primary key key di customer tabel
            "id" // primary key di wallets tabel
        );
    }


    // untuk relasi one to many bisa menggunakan method hasMany() pada model, untuk relasi bidirectional (dua arah) menggunakan method belongsTo() pada model yg lain
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, "customer_id", "id");
    }


    // relasi ManyToMany harus membuat tabel jembatan sebagai tabel penengahnya (intermediate Table)
    // untuk membuat relasi manyToMany bisa menggunakan belongsToMany di kedua modelnya
    // contoh relasi manyToMany antara model Customer dan Product, tabel customers_likes_product sebagai jembatannya
    // pivot(); untuk mengambil semua isi kolom di intermediate table, secare default hanya foreign key model 1 dan 2 saja yang akan diquery di Pivot Attribute, jika ingin menambhakan kolom lain, bisa tambahkan pada relasi BelongsToMany dengan method withPivot(namaKolom)
    public function likeProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, "customers_likes_products", "customer_id", "product_id")
            ->withPivot("created_at");
    }
    // wherePivot(namaKolom); untuk mengambil semua isi kolom intermediate table berdasarkan filter kondisi tertentu
    // contoh mengambil products yang di like customer A pada waktu 7 hari terakhir
    public function likeProductsLastWeek(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, "customers_likes_products", "customer_id", "product_id")
            ->withPivot("created_at")
            ->wherePivot("created_at", ">=", Date::now()->addDays(-7));
    }
}
