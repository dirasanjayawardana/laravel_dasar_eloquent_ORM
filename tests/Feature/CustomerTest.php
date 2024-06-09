<?php

namespace Tests\Feature;

use App\Models\Relations\OneToOne\Customer;
use App\Models\Relations\OneToOne\Wallet;
use Database\Seeders\CategorySeeder;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\ImageSeeder;
use Database\Seeders\ProductSeeder;
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


    // relasi ManyToMany harus membuat tabel jembatan sebagai tabel penengahnya (intermediate Table)
    // untuk membuat relasi manyToMany bisa menggunakan belongsToMany di kedua modelnya
    // contoh relasi manyToMany antara model Customer dan Product, tabel customers_likes_product sebagai jembatannya
    public function testManyToMany()
    {
        $this->seed([CustomerSeeder::class, CategorySeeder::class, ProductSeeder::class]);

        $customer = Customer::find("DIRA");
        self::assertNotNull($customer);

        // attach(id) --> untuk menambah relasi, akan melakukan insert data ke customers_likes_products
        $customer->likeProducts()->attach("1");

        $products = $customer->likeProducts;
        self::assertCount(1, $products);

        self::assertEquals("1", $products[0]->id);
    }
    // untuk menghapus relasi oneToOne dan oneToMany cukup mudah dengan menhapus kolom foreign key nya
    // unutk menghpuas relasi many to many bisa menggunakan method detach() pada BelongsToMany
    public function testManyToManyDetach()
    {
        $this->testManyToMany();

        // detach(id) --> akan menghapus relasi, akan hapus data di customers_likes_products
        $customer = Customer::find("DIRA");
        $customer->likeProducts()->detach("1");

        $products = $customer->likeProducts;
        self::assertCount(0, $products);
    }
    // pivot(); untuk mengambil semua isi kolom di intermediate table, secare default hanya foreign key model 1 dan 2 saja yang akan diquery di Pivot Attribute, jika ingin menambhakan kolom lain, bisa tambahkan pada relasi BelongsToMany dengan method withPivot()
    public function testPivotAttribute()
    {
        $this->testManyToMany();

        $customer = Customer::find("DIRA");
        $products = $customer->likeProducts;

        foreach ($products as $product) {
            $pivot = $product->pivot;
            self::assertNotNull($pivot);
            self::assertNotNull($pivot->customer_id);
            self::assertNotNull($pivot->product_id);
            self::assertNotNull($pivot->created_at);
        }
    }
    // wherePivot(namaKolom); untuk mengambil semua isi kolom intermediate table berdasarkan filter kondisi tertentu
    // contoh mengambil products yang di like customer A pada waktu 7 hari terakhir
    public function testPivotAttributeCondition()
    {
        $this->testManyToMany();

        $customer = Customer::find("DIRA");
        $products = $customer->likeProductsLastWeek;

        foreach ($products as $product) {
            $pivot = $product->pivot;
            self::assertNotNull($pivot);
            self::assertNotNull($pivot->customer_id);
            self::assertNotNull($pivot->product_id);
            self::assertNotNull($pivot->created_at);
        }
    }


    // menggunakan pivot model Like sebagai model penengah untuk relasi manyToMany
    public function testPivotModel()
    {
        $this->testManyToMany();

        $customer = Customer::find("DIRA");
        $products = $customer->likeProducts;

        foreach ($products as $product){
            $pivot = $product->pivot; // object Model Like
            self::assertNotNull($pivot);
            self::assertNotNull($pivot->customer_id);
            self::assertNotNull($pivot->product_id);
            self::assertNotNull($pivot->created_at);

            self::assertNotNull($pivot->customer);

            self::assertNotNull($pivot->product);
        }
    }


    // Polymorphic Relationships (relasi antar tabel namun relasinya bisa berbeda model)
    // namun relasi ini tidak dianjurkan karena dalam relation database, satu kolom foreign key hanya bisa mnegacu ke satu tabel
    // OneToOne Polymorphic mirip seperti relasi OneToOne, hanya saja relasinya bisa lebih dari satu model
    // contoh Customer dan Product punya satu Image, artinya Model Image berelasi OneToOne dengan Customer dan Product
    public function testOneToOnePolymorphic()
    {
        $this->seed([CustomerSeeder::class, ImageSeeder::class]);

        $customer = Customer::find("DIRA");
        self::assertNotNull($customer);

        $image = $customer->image;
        self::assertNotNull($image);

        self::assertEquals("https://www.dirapp.com/image/1.jpg", $image->url);
    }
}
