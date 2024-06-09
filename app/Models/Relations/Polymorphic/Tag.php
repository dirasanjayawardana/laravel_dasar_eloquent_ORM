<?php

namespace App\Models\Relations\Polymorphic;

use App\Models\Relations\OneToMany\Product;
use App\Models\Voucher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Tag extends Model
{
    // Polymorphic Relationships (relasi antar tabel namun relasinya bisa beberapa model)
    // ManyToMany Polymorphic mirip seperti relasi ManyToMany, namun relasinya bisa beberapa model
    // contoh relasi ManyToMany Polymorphic pada Tag dengan Product dan Voucher, artinya bisa satu Tag bisa digunakan dibanyak Voucher dan Product, satu Voucher dan Product bisa punya banyak Tag
    protected $table = "tags";
    protected $primaryKey = "id";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = false;

    public function products(): MorphToMany
    {
        return $this->morphedByMany(Product::class, "taggable");
    }

    public function vouchers(): MorphToMany
    {
        return $this->morphedByMany(Voucher::class, "taggable");
    }
}
