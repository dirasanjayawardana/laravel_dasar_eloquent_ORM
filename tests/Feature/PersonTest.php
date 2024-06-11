<?php

namespace Tests\Feature;

use App\Models\Person;
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
}
