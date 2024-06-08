<?php

namespace App\Models\Relations\OneThrough;

use App\Models\Relations\OneToOne\Wallet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VirtualAccount extends Model
{
    // untuk relasi one to one bisa menggunakan method hasOne() pada model, untuk relasi bidirectional (dua arah) menggunakan method belongsTo() pada model yg lain
    protected $table = "virtual_accounts";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $incrementing = true;
    public $timestamps = false;

    
    // relasi HasOneThrough (relasi antar model yang tidak berhubungan langsung, tetapi melewati model lain)
    // contoh relasi model Customer oneToOne ke Wallet, model Wallet oneToOne VirtualAccount, maka bisa membuat relasi antara Customer dengan VirtualAccount yang melewati model Wallet
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class, "wallet_id", "id");
    }
}
