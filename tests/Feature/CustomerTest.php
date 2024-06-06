<?php

namespace Tests\Feature;

use App\Models\Relations\OneToOne\Customer;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\WalletSeeder;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    // ONE TO ONE
    // untuk relasi one to one bisa menggunakan method hasOne() pada model, untuk relasi bidirectional (dua arah) menggunakan method belongsTo() pada model yg lain
    public function testOneToOne()
    {
        $this->seed([CustomerSeeder::class, WalletSeeder::class]);

        $customer = Customer::find("DIRA");
        self::assertNotNull($customer);

        // $wallet = Wallet::where("customer_id", $customer->id)->first();
        $wallet = $customer->wallet; // memanggil data pada tabel wallet tanpa query manual berdasarkan id
        self::assertNotNull($wallet);

        self::assertEquals(1000000, $wallet->amount);
    }
}
