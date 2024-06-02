<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // Model --> Representasi dari Table di database
    // atribute di Model, bisa dioverride untuk menambahkan informasi tentang schema table
    // $table, $primaryKey, $keyType, $incrementing, $timestamp dll
    // secara default eloquent terdapat kolom created_at dan updated_at, jika tidak butuh bisa mengoverride $timestamp menjadi false
    protected $table = "categories";
    protected $primaryKey = "id";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = false;
}
