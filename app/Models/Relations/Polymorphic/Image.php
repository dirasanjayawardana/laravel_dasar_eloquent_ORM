<?php

namespace App\Models\Relations\Polymorphic;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Image extends Model
{
    // Polymorphic Relationships (relasi antar tabel namun relasinya bisa berbeda model)
    // namun relasi ini tidak dianjurkan karena dalam relation database, satu kolom foreign key hanya bisa mnegacu ke satu tabel
    // OneToOne Polymorphic mirip seperti relasi OneToOne, hanya saja relasinya bisa lebih dari satu model
    // contoh Customer dan Product punya satu Image, artinya Model Image berelasi OneToOne dengan Customer dan Product
    protected $table = "images";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $incrementing = true;
    public $timestamps = false;

    // imageable() tidak ditentukan relasi denga Product atau Customer, karena bisa berelasi dengan dua table tersebut
    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }
}
