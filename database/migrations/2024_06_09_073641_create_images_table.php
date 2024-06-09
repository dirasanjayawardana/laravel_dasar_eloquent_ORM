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
        // saat membuat relasi polymorphis, harus membuat kolom nama relasinya, misal imageable
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string("url", 255)->nullable(false);
            $table->string("imageable_id", 100)->nullable(false); // berisi foreign key pada primary key di tabel relasi
            $table->string("imageable_type", 200)->nullable(false); // berisi tipe model pada primary key di tabel relasi, biasanya berupa class nya atau nama tabel nya
            $table->unique(["imageable_id", "imageable_type"]);
        });
    }

    // Reverse the migrations.
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
