<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class IsActiveScope implements Scope
{
    // Query Scope (cara menambahkan kondisi query secara otomatis, agar setiap melakukan query akan mengikuti kondisi yang telah ditentukan)
    // Query Global Scope --> kondisi ditambahkan di model, secara otomatis ketika melakukan query, kondisi yang ditambahkan akan diterapkan di query builder, contoh ketika menggunakan trait SoftDelete otomatis menambahkan kondisi "where deleted_at = null"
    // contoh menambahkan kondisi Active dan Non Active, dimana setiap melakukan query akan selalu mengambil data yg Active saja di kolom is_active
    // php artisan make:scope NamaScope --> membuat scope di app/Models/Scopes, lalu tambahkan kondisi scope yg sudah dibuat, lalu tambahkan scope ke Model dengan mengoverride booted() dan menggunakan method addGlobalScope(scope)
    public function apply(Builder $builder, Model $model): void
    {
        $builder->where("is_active", "=", true);
    }
}
