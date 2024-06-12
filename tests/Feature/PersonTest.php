<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Person;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PersonTest extends TestCase
{
    // accessor (fitur untuk mengubah data ketika diakses), mutator (fitur untuk mengubah data ketika di set)
    // caranya dengan membuat function yang mengembalikan object Attribute
    // untuk mengakases method accessor dan mutator dengan menggunakan nama method tetapi dengan format snake_case, buka camelCase
    public function testPerson()
    {
        $person = new Person();
        $person->first_name = "Dira";
        $person->last_name = "Sanjaya";
        $person->save();

        // aksesor (akan mengeksekusi named parameter get di method)
        self::assertEquals("DIRA Sanjaya", $person->full_name);

        // mutator (akan mengeksekusi named parameter set di method)
        $person->full_name = "Sanjaya Wardana";
        $person->save();

        self::assertEquals("SANJAYA", $person->first_name);
        self::assertEquals("Wardana", $person->last_name);
    }


    // Atribute casting (fitur untuk melakukan konvresi tipe data secara otomatis dari tipe data di database dengan tipe data yang ada di PHP)
    // dengan cara mengoverride $casts=['namaKolom' => "tipeDataDiPHP"]
    public function testAttributeCasting()
    {
        $person = new Person();
        $person->first_name = "Eko";
        $person->last_name = "Khannedy";
        $person->save();

        self::assertNotNull($person->created_at);
        self::assertNotNull($person->updated_at);
        self::assertInstanceOf(Carbon::class, $person->created_at);
        self::assertInstanceOf(Carbon::class, $person->updated_at);
    }
    public function testCustomCasts()
    {
        $person = new Person();
        $person->first_name = "Dira";
        $person->last_name = "Sanjaya";
        $person->address = new Address("Jalan Belum Jadi", "Jakarta", "Indonesia", "11111");
        $person->save();

        self::assertNotNull($person->created_at);
        self::assertNotNull($person->updated_at);
        self::assertInstanceOf(Carbon::class, $person->created_at);
        self::assertInstanceOf(Carbon::class, $person->updated_at);
        self::assertEquals("Jalan Belum Jadi", $person->address->street);
        self::assertEquals("Jakarta", $person->address->city);
        self::assertEquals("Indonesia", $person->address->country);
        self::assertEquals("11111", $person->address->postal_code);
    }
}
