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
        Schema::create('comments', function (Blueprint $table) {
            $table->integer("id")->autoIncrement(); // ketika diset menjadi autoIncrement akan otomatis menjadi primaryKey
            $table->string("email", 100)->nullable(false);
            $table->string("title", 200)->nullable(false);
            $table->text("comment")->nullable(true);
            $table->timestamps(); // otomatis akan membuat tabel created_at dan upadated_at yang akan terisi otomatis dengan tipe timestamp
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
