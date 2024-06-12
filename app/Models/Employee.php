<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    // saat membuat object Model, biasanya harus ubah tiap atribut satu satu secara manual
    // trait HasFactory (implementasi dari design patterns Factory Patterns)
    // dimana kita membuat class Factory yang digunakan untuk membuat object
    // sehingga ketika membuat object yang hampir sama, bisa menggunakan Factory

    // nama Factory secara default adalah namaModel + Factory
    // untuk membuat class Factory dengan menggunakan "php artisan make:factory NamaFactory" akan disimpan di database/factories

    use HasFactory;

    protected $table = "employees";
    protected $primaryKey = "id";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = true;
}
