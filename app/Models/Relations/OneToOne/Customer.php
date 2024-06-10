<?php

namespace App\Models\Relations\OneToOne;

use App\Models\Relations\ManyThrough\Review;
use App\Models\Relations\ManyToMany\Like;
use App\Models\Relations\OneThrough\VirtualAccount;
use App\Models\Relations\OneToMany\Product;
use App\Models\Relations\Polymorphic\Image;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphOne;
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
    // using(Like::class); menggunakan pivot model Like sebagai model penengahnya
    public function likeProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, "customers_likes_products", "customer_id", "product_id")
            ->withPivot("created_at")
            ->using(Like::class);
    }
    // wherePivot(namaKolom); untuk mengambil semua isi kolom intermediate table berdasarkan filter kondisi tertentu
    // contoh mengambil products yang di like customer A pada waktu 7 hari terakhir
    public function likeProductsLastWeek(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, "customers_likes_products", "customer_id", "product_id")
            ->withPivot("created_at")
            ->wherePivot("created_at", ">=", Date::now()->addDays(-7))
            ->using(Like::class);
    }


    // Polymorphic Relationships (relasi antar tabel namun relasinya bisa berbeda model)
    // namun relasi ini tidak dianjurkan karena dalam relation database, satu kolom foreign key hanya bisa mnegacu ke satu tabel
    // OneToOne Polymorphic mirip seperti relasi OneToOne, hanya saja relasinya bisa lebih dari satu model
    // contoh Customer dan Product punya satu Image, artinya Model Image berelasi OneToOne dengan Customer dan Product
    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, "imageable");
    }


    // Lazy Loading
    // secara default semua relasi akan diload(ambil) datanya ketika attributenya dipanggil, baru laravel akan melakukan query
    // Eager Loading
    // langsung mengambil data secara langsung ketika kita mengambil data Model
    // bisa menggunakan Query Builder dengan method with([methodRelation]) atau bisa hardcode di Modelnya dengan override $with
    protected $with = ["wallet"];
}
