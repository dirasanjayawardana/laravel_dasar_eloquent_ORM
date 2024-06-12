<?php

namespace Tests\Feature;

use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    // saat membuat object Model, biasanya harus ubah tiap atribut satu satu secara manual
    // trait HasFactory (implementasi dari design patterns Factory Patterns)
    // dimana kita membuat class Factory yang digunakan untuk membuat object
    // sehingga ketika membuat object yang hampir sama, bisa menggunakan Factory

    // untuk membuat object dengan factory --> NamaModel::factory()->namaState()->make();
    // untuk membuat object dengan factory dan langsung menyimpan ke database --> NamaModel::factory()->namaState()->create(["key"=>"value"]);
    // jika data tidak disebutkan di method create() maka akan menggunakan nilai default dari factory
    public function testFactory()
    {
        $employee1 = Employee::factory()->programmer()->make();
        $employee1->id = '1';
        $employee1->name = 'Employee 1';
        $employee1->save();

        self::assertNotNull(Employee::where('id', '1')->first());

        $employee2 = Employee::factory()->seniorProgrammer()->create([
            'id' => '2',
            'name' => 'Employee 2'
        ]);
        self::assertNotNull($employee2);
        self::assertNotNull(Employee::where('id', '2')->first());
    }
}
