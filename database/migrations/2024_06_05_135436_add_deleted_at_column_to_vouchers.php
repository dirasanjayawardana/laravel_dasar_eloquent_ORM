<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Schema::table(namaTable, callback) --> alter table, merubah table
    // Schema::create(namaTable, callback) --> membuat tabel baru, method pada callback:
    // tipeData('namaKolom', size)
    // nullable(condition)
    // primary()
    // default(value)
    // useCurrent()
    // foreign(namaKolom)->refferences(kolomReferensi)->on(tableReferensi)

    // Run the migrations.
    public function up(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->softDeletes(); // menambah kolom deleted_at dengan tipe timestamp
        });
    }

    // Reverse the migrations.
    public function down(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
