<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    // Model --> Representasi dari Table di database
    // atribute di Model, bisa dioverride untuk menambahkan informasi tentang schema table
    // $table, $primaryKey, $keyType, $incrementing, $timestamp dll
    // secara default eloquent terdapat kolom created_at dan updated_at, jika tidak butuh bisa mengoverride $timestamp menjadi false

    // trait HasUuid (untuk menggunakan UUID pada model), trait SoftDelete (tidak benar-benar menghapus data, hanya ditandai bahwa suatu data telah dihapus di kolom deleted_at, harus ada kolom deleted_at dengan tipe timestamp)
    use HasUuids, SoftDeletes;

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


    // Query Scope (cara menambahkan kondisi query secara otomatis, agar setiap melakukan query akan mengikuti kondisi yang telah ditentukan)
    // Query Local Scope (secara default tidak aktif, kecuali mengaktifkannya ketika melakukan query)
    // untuk membuat query local scope dengan membuat method di Model dengan awalan scope lalu diikuti dengan nama scopenya, contoh scopeActive(), scopeNonActive(), yang mana method tersebut membutuhkan parameter Builder untuk menambahkan kondisi
    // untuk menggunakan local scope, dengan memanggil methodnya, diawali dengan lowecase tanpa prefix "scope"
    public function scopeActive(Builder $builder): void
    {
        $builder->where('is_active', "=", true);
    }
    public function scopeNonActive(Builder $builder): void
    {
        $builder->where('is_active', "=", false);
    }
}
