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

    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->uuid("id")->nullable(false)->primary();
            $table->string("name", 100)->nullable(false);
            $table->string("voucher_code", 200)->nullable(false);
            $table->timestamp("created_at")->nullable(false)->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
