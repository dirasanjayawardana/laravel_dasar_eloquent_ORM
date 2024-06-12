<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Relations\OneToMany\Product;
use App\Models\Voucher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createCommentsForProduct();
        $this->createCommentsForVoucher();
    }

    private function createCommentsForProduct() : void
    {
        $product = Product::find("1");

        $comment = new Comment();
        $comment->email = "dira@email.com";
        $comment->title = "Title";
        $comment->commentable_id = $product->id;
        $comment->commentable_type = "product"; // memanggil alias untuk type polymorphic di app/Providers/AppServicePorvider
        $comment->save();
    }

    private function createCommentsForVoucher():void
    {
        $voucher = Voucher::first();

        $comment = new Comment();
        $comment->email = "dira@email.com";
        $comment->title = "Title";
        $comment->commentable_id = $voucher->id;
        $comment->commentable_type = "voucher"; // memanggil alias untuk type polymorphic di app/Providers/AppServicePorvider
        $comment->save();
    }
}

