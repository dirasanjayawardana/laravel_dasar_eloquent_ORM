<?php

namespace Database\Seeders;

use App\Models\Relations\OneThrough\VirtualAccount;
use App\Models\Relations\OneToOne\Wallet;
use Illuminate\Database\Seeder;

class VirtualAccountSeeder extends Seeder
{
    public function run(): void
    {
        $wallet = Wallet::where("customer_id", "DIRA")->firstOrFail();

        $virtualAccount = new VirtualAccount();
        $virtualAccount->bank = "BCA";
        $virtualAccount->va_number = "123456789";
        $virtualAccount->wallet_id = $wallet->id;
        $virtualAccount->save();
    }
}
