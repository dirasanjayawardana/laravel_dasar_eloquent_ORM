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
        Schema::table('comments', function (Blueprint $table) {
            $table->string("commentable_id", 100)->nullable(false);
            $table->string("commentable_type", 100)->nullable(false);
        });
    }

    // Reverse the migrations.
    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn(["commentable_id", "commentable_type"]);
        });
    }
};
