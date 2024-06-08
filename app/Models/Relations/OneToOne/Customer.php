<?php

namespace App\Models\Relations\OneToOne;

use App\Models\Relations\ManyThrough\Review;
use App\Models\Relations\OneThrough\VirtualAccount;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

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
}
