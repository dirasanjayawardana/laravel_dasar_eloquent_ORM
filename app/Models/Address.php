<?php

namespace App\Models;

class Address
{
    // custom tipe data untuk custom cast di class Person
    // kemudian jalankan "php artisan make:cast AsAddress" akan generate cast di app/Casts
    public string $street;
    public string $city;
    public string $country;
    public string $postal_code;

    public function __construct(string $street, string $city, string $country, string $postal_code)
    {
        $this->street = $street;
        $this->city = $city;
        $this->country = $country;
        $this->postal_code = $postal_code;
    }
}
