<?php

namespace App\Models\Relations\ManyThrough;

use App\Models\Relations\OneToMany\Product;
use App\Models\Relations\OneToOne\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $table = "reviews";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $incrementing = true;
    public $timestamps = false;


    // relasi HasManyThrough (relasi antar model yang tidak berhubungan langsung, tetapi melewati model lain)
    // contoh relasi model Category oneToMany ke Proudct, model Product oneToMany ke Review, maka bisa membuat relasi antara Category dengan Review yang melewati model Product
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, "product_id", "id");
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, "customer_id", "id");
    }
}
