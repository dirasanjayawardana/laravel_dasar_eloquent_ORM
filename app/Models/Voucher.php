<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    // Model --> Representasi dari Table di database
    // atribute di Model, bisa dioverride untuk menambahkan informasi tentang schema table
    // $table, $primaryKey, $keyType, $incrementing, $timestamp dll
    // secara default eloquent terdapat kolom created_at dan updated_at, jika tidak butuh bisa mengoverride $timestamp menjadi false

    // trait HasUuid (untuk menggunakan UUID pada model)
    use HasUuids;

    protected $table = "vouchers";
    protected $primaryKey = "id";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = false;

    // jika ingin menggunakan UUID selain di primaryKey, bisa dengan mengoverride method uniquelds()
    // contoh mendaftarkan kolom "voucher_code" sebagai UUID yang akan diisi otomatis
    public function uniqueIds(): array
    {
        return [$this->primaryKey, "voucher_code"];
    }
}
