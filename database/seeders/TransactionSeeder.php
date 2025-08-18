<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Str;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil user kasir
        $kasir = User::where('role', 'kasir')->first();
        if (!$kasir) {
            $this->command->error('Tidak ada user kasir yang ditemukan. Jalankan UserSeeder terlebih dahulu.');
            return;
        }

        // Ambil beberapa produk
        $products = Product::take(5)->get();
        if ($products->isEmpty()) {
            $this->command->error('Tidak ada produk yang ditemukan. Jalankan ProductSeeder terlebih dahulu.');
            return;
        }

        // Buat beberapa transaksi sample
        for ($i = 1; $i <= 3; $i++) {
            $totalAmount = 0;
            $cartItems = [];

            // Buat 2-4 item per transaksi
            $numItems = rand(2, 4);
            for ($j = 0; $j < $numItems; $j++) {
                $product = $products->random();
                $quantity = rand(1, 3);
                $price = $product->selling_price;
                $subtotal = $price * $quantity;
                
                $cartItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $subtotal
                ];
                
                $totalAmount += $subtotal;
            }

            $totalPaid = $totalAmount + rand(0, 5000); // Tambah kembalian random
            $changeAmount = $totalPaid - $totalAmount;

            $transaction = Transaction::create([
                'invoice_number' => 'INV-' . Str::random(10),
                'user_id' => $kasir->id,
                'total_amount' => $totalAmount,
                'total_paid' => $totalPaid,
                'change_amount' => $changeAmount,
            ]);

            // Buat detail transaksi
            foreach ($cartItems as $item) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            $this->command->info("Transaksi {$i} berhasil dibuat: {$transaction->invoice_number}");
        }

        $this->command->info('Seeder transaksi selesai!');
    }
}
