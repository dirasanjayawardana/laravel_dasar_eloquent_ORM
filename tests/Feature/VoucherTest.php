<?php

namespace Tests\Feature;

use App\Models\Voucher;
use Database\Seeders\VoucherSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

// override setUp method di TestCase
class VoucherTest extends TestCase
{
    // CREATE
    // create data baru tanpa input id, karena otomatis diisi dengan UUID
    public function testCreateVoucher()
    {
        $voucher = new Voucher();
        $voucher->name = "Sample Voucher";
        $voucher->voucher_code = "23414124214";
        $voucher->save();
        // insert into `vouchers` (`name`, `voucher_code`, `id`) values (?, ?, ?)

        self::assertNotNull($voucher->id);
    }


    // create data baru tanpa input id dan voucher_code, karena otomatis diisi dengan UUID
    public function testCreateVoucherUUID()
    {
        $voucher = new Voucher();
        $voucher->name = "Sample Voucher";
        $voucher->save();

        self::assertNotNull($voucher->id);
        self::assertNotNull($voucher->voucher_code);
    }


    // SOFT DELETE
    // ketika melakukan soft delete, sama seperti melakukan delete seperti biasa dengan delete()
    // namun secara otomatis laravel tidak benar benar menghapus datanya, hanya mengisi kolom deleted_at
    // ketika dilakukan select, maka data yg sudah di softDelete tidak akan didapat
    // forceDelete() --> untuk memaksa benar benar menghapus datanya
    // withTrashed() --> mendapatkan semua data termasuk yg sudah di softDelete
    public function testSoftDelete()
    {
        $this->seed(VoucherSeeder::class);

        $voucher = Voucher::where('name', '=', 'Sample Voucher')->first();
        $voucher->delete();

        $voucher = Voucher::where('name', '=', 'Sample Voucher')->first();
        self::assertNull($voucher);

        $voucher = Voucher::withTrashed()->where('name', '=', 'Sample Voucher')->first();
        self::assertNotNull($voucher);
    }


    // Query Scope (cara menambahkan kondisi query secara otomatis, agar setiap melakukan query akan mengikuti kondisi yang telah ditentukan)
    // Query Local Scope (secara default tidak aktif, kecuali mengaktifkannya ketika melakukan query)
    // untuk membuat query local scope dengan membuat method di Model dengan awalan scope lalu diikuti dengan nama scopenya, contoh scopeActive(), scopeNonActive(), yang mana method tersebut membutuhkan parameter Builder untuk menambahkan kondisi
    // untuk menggunakan local scope, dengan memanggil methodnya (namaScope()), diawali dengan lowecase tanpa prefix "scope"
    public function testLocalScope()
    {
        $voucher = new Voucher();
        $voucher->name = "Sample Voucher";
        $voucher->is_active = true;
        $voucher->save();

        $total = Voucher::active()->count();
        self::assertEquals(1, $total);

        $total = Voucher::nonActive()->count();
        self::assertEquals(0, $total);
    }

}
