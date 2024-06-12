<?php

namespace App\Models;

use App\Casts\AsAddress;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $table = "persons";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $incrementing = true;
    public $timestamps = true;


    // Atribute casting (fitur untuk melakukan konvresi tipe data secara otomatis dari tipe data di database dengan tipe data yang ada di PHP)
    // dengan cara mengoverride $casts=['namaKolom' => "tipeDataDiPHP"]; cara kerjanya mirip accessor dan mutator
    protected $casts = [
        'address' => AsAddress::class, // custom cast, untuk tipe data custom
        "created_at" => "datetime",
        "updated_at" => "datetime"
    ];


    // accessor (fitur untuk mengubah data ketika diakses), mutator (fitur untuk mengubah data ketika di set), menambah logic ketika akses data atau set data
    // caranya dengan membuat function yang mengembalikan object Attribute
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                return $this->first_name . ' ' . $this->last_name;
            },
            set: function (string $value): array {
                $names = explode(" ", $value);
                return [
                    'first_name' => $names[0],
                    'last_name' => $names[1] ?? ''
                ];
            }
        );
    }


    // mengambil value asli dari data base, dengan memberikan parameter pada function di named paramter
    protected function firstName(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes): string {
                return strtoupper($value);
            },
            set: function ($value): array {
                return [
                    'first_name' => strtoupper($value)
                ];
            }
        );
    }
}
