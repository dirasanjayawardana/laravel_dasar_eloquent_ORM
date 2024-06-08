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
        Schema::create('customers_likes_products', function (Blueprint $table) {
            $table->string('customer_id', 100)->nullable(false);
            $table->string('product_id', 100)->nullable(false);
            $table->primary(['customer_id', 'product_id']);
            $table->foreign("customer_id")->on("customers")->references("id");
            $table->foreign("product_id")->on("products")->references("id");
        });
    }

    // Reverse the migrations.
    public function down(): void
    {
        Schema::dropIfExists('customers_likes_products');
    }
};
