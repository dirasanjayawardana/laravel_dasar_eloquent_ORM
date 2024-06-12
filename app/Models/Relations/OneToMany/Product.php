<?php

namespace App\Models\Relations\OneToMany;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Relations\ManyThrough\Review;
use App\Models\Relations\ManyToMany\Like;
use App\Models\Relations\OneToOne\Customer;
use App\Models\Relations\Polymorphic\Image;
use App\Models\Relations\Polymorphic\Tag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Product extends Model
{
    protected $table = "products";
    protected $primaryKey = "id";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = false;


    // untuk menyembunyikan atau mengecualikan kolom mana saja yang tidak ingin dilakukan serialization (toArray() atau toJSON())
    protected $hidden = [
        'category_id'
    ];


    // untuk relasi one to many bisa menggunakan method hasMany() pada model, untuk relasi bidirectional (dua arah) menggunakan method belongsTo() pada model yg lain
    // satu Category memiliki banyak relasi di Product, satu Product hanya ada satu relasi di Category
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, "category_id", "id");
    }


    // untuk relasi one to many bisa menggunakan method hasMany() pada model, untuk relasi bidirectional (dua arah) menggunakan method belongsTo() pada model yg lain
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, "product_id", "id");
    }


    // relasi ManyToMany harus membuat tabel jembatan sebagai tabel penengahnya (intermediate Table)
    // untuk membuat relasi manyToMany bisa menggunakan belongsToMany di kedua modelnya
    // contoh relasi manyToMany antara model Customer dan Product, tabel customers_likes_product sebagai jembatannya
    // pivot(); untuk mengambil semua isi kolom di intermediate table, secare default hanya foreign key model 1 dan 2 saja yang akan diquery di Pivot Attribute, jika ingin menambhakan kolom lain, bisa tambahkan pada relasi BelongsToMany dengan method withPivot(namaKolom)
    public function likedByCustomers(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class, "customers_likes_products", "product_id", "customer_id")
            ->withPivot("created_at")
            ->using(Like::class);
    }


    // Polymorphic Relationships (relasi antar tabel namun relasinya bisa beberapa model)
    // namun relasi ini tidak dianjurkan karena dalam relation database, satu kolom foreign key hanya bisa mnegacu ke satu tabel
    // OneToOne Polymorphic mirip seperti relasi OneToOne, hanya saja relasinya bisa lebih dari satu model
    // contoh Customer dan Product punya satu Image, artinya Model Image berelasi OneToOne dengan Customer dan Product
    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, "imageable");
    }


    // OneToMany Polymorphic mirip seperti relasi OneToMany, bedanya pada tabel tidak menambahkan Unique Constraint, karena bisa lebih dari satu
    // contoh relasi OneToMany Polymorphic pada Comment dengan Product dan Voucher, artinya bisa menambahkan lebih dari satu Comment ke Product dan Voucher
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, "commentable");
    }


    // HasOneOfMany (mengambil satu data saja dari relasi one to many)
    // sebenarnya bisa menggunakan query builder, $model1->model2()->where("kondisi")-->get()
    // untuk di polymorphic bisa menggunakan MorphOne
    public function latestComment(): MorphOne
    {
        return $this->morphOne(Comment::class, "commentable")
            ->latest("created_at");
    }
    public function oldestComment(): MorphOne
    {
        return $this->morphOne(Comment::class, "commentable")
            ->oldest("created_at");
    }


    // ManyToMany Polymorphic mirip seperti relasi ManyToMany, namun relasinya bisa beberapa model
    // contoh relasi ManyToMany Polymorphic pada Tag dengan Product dan Voucher, artinya bisa satu Tag bisa digunakan dibanyak Voucher dan Product, satu Voucher dan Product bisa punya banyak Tag
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, "taggable");
    }
}
