<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Comment extends Model
{
    // Model --> Representasi dari Table di database
    // atribute di Model, bisa dioverride untuk menambahkan informasi tentang schema table
    // $table, $primaryKey, $keyType, $incrementing, $timestamp dll
    // secara default eloquent terdapat kolom created_at dan updated_at, jika tidak butuh bisa mengoverride $timestamp menjadi false

    protected $table = "comments";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $incrementing = true;
    public $timestamps = true;

    // override $attributes (untuk menambahkan default value ketika field tidak diisi)
    protected $attributes = [
        "title" => "Sample Title",
        "comment" => "Sample Comment"
    ];


    // Polymorphic Relationships (relasi antar tabel namun relasinya bisa berbeda model)
    // namun relasi ini tidak dianjurkan karena dalam relation database, satu kolom foreign key hanya bisa mnegacu ke satu tabel
    // OneToMany Polymorphic mirip seperti relasi OneToMany, bedanya pada tabel tidak menambahkan Unique Constraint, karena bisa lebih dari satu
    // contoh relasi OneToMany Polymorphic pada Comment dengan Product dan Voucher, artinya bisa menambahkan lebih dari satu Comment ke Product dan Voucher
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }
}
