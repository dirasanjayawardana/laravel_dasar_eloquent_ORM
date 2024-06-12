<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    // saat membuat object Model, biasanya harus ubah tiap atribut satu satu secara manual
    // trait HasFactory (implementasi dari design patterns Factory Patterns)
    // dimana kita membuat class Factory yang digunakan untuk membuat object
    // sehingga ketika membuat object yang hampir sama, bisa menggunakan Factory

    // nama Factory secara default adalah namaModel + Factory
    // untuk membuat class Factory dengan menggunakan "php artisan make:factory NamaFactory" akan disimpan di database/factories

    // saat membuat factory wajib override method definition()
    // pada method definition berisi key value untuk mengisi default value atribute yang dibutuhkan di Model Employee
    public function definition(): array
    {
        return [
            'id' => '',
            'name' => '',
            'title' => '',
            'salary' => 0
        ];
    }


    // jika ingin membuat state lainnya, state akan menggunakan data dari method definition() jika tidak diisikan attributenya
    public function programmer(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'title' => 'Programmer',
                'salary' => 5000000
            ];
        });
    }

    public function seniorProgrammer(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'title' => 'Senior Programmer',
                'salary' => 10000000
            ];
        });
    }
}
