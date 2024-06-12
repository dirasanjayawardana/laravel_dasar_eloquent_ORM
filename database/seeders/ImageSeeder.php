<?php

namespace Database\Seeders;

use App\Models\Relations\OneToMany\Product;
use App\Models\Relations\OneToOne\Customer;
use App\Models\Relations\Polymorphic\Image;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        {
            $image = new Image();
            $image->url = "https://www.dirapp.com/image/1.jpg";
            $image->imageable_id = "DIRA";
            $image->imageable_type = "customer"; // memanggil alias untuk type polymorphic di app/Providers/AppServicePorvider
            $image->save();
        }
        {
            $image = new Image();
            $image->url = "https://www.dirapp.com/image/2.jpg";
            $image->imageable_id = "1";
            $image->imageable_type = "product"; // memanggil alias untuk type polymorphic di app/Providers/AppServicePorvider
            $image->save();
        }
    }
}
