<?php

namespace Tests\Feature;

use App\Models\Voucher;
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
}
