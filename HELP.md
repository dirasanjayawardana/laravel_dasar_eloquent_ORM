# Laravel Eloquent
Laravel Eloquent --> fitur ORM (Object Relational Mapping) di laravel --> membuat object di aplikasi yang merepresentasikan data(table) di database, sehingga untuk melakukan manipulasi data(table) dilakukan dengan object tersebut

## Model
Representasi dari Table di database

### Membuat Model
`php artisan make:model NamaModel`

### menambahkan fitur pendukung seperti migrations dan seeding
`--migration` untuk menambahkan migration
`--seed` untuk menambahkan seeding
`php artisan make:model Category --migration --seed`
