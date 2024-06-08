<?php

namespace Tests\Feature;

use App\Models\Relations\OneToOne\Customer;
use App\Models\Relations\OneToOne\Wallet;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\VirtualAccountSeeder;
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


    // ketika suatu model memiliki relasi ke model lain, maka bisa melakukan CRUD model lain dengan menggunakan method relationship
    public function testOneToOneQuery()
    {
        $customer = new Customer();
        $customer->id = "DIRA";
        $customer->name = "Dira";
        $customer->email = "dira@email.com";
        $customer->save();

        $wallet = new Wallet();
        $wallet->amount = 1000000;

        // melakukan query ke model wallet dengan model customer
        $customer->wallet()->save($wallet);

        self::assertNotNull($wallet->customer_id);
    }


    // relasi HasOneThrough (relasi antar model yang tidak berhubungan langsung, tetapi melewati model lain)
    // contoh relasi model Customer oneToOne ke Wallet, model Wallet oneToOne VirtualAccount, maka bisa membuat relasi antara Customer dengan VirtualAccount yang melewati model Wallet
    public function testHasOneThrough()
    {
        $this->seed([CustomerSeeder::class, WalletSeeder::class, VirtualAccountSeeder::class]);

        $customer = Customer::find("DIRA");
        self::assertNotNull($customer);

        $virtualAccount = $customer->virtualAccount;
        self::assertNotNull($virtualAccount);
        self::assertEquals("BCA", $virtualAccount->bank);
    }
}
