<?php

namespace Database\Seeders;

use App\Models\Cat;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerDummySeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password');

        Pelanggan::query()->update(['password' => $password]);

        $cats = Cat::where('stok', '>', 0)->get();

        if ($cats->isEmpty()) {
            $this->command->warn('No cats with stock available.');

            return;
        }

        $pelanggans = Pelanggan::all();

        $transactions = [
            ['pelanggan_id' => 1, 'cat_id' => 3, 'jumlah' => 4, 'date' => '2026-07-01'],
            ['pelanggan_id' => 1, 'cat_id' => 5, 'jumlah' => 2, 'date' => '2026-07-03'],
            ['pelanggan_id' => 1, 'cat_id' => 8, 'jumlah' => 6, 'date' => '2026-07-05'],
            ['pelanggan_id' => 1, 'cat_id' => 10, 'jumlah' => 3, 'date' => '2026-07-08'],
            ['pelanggan_id' => 1, 'cat_id' => 11, 'jumlah' => 5, 'date' => '2026-07-12'],
            ['pelanggan_id' => 1, 'cat_id' => 2, 'jumlah' => 1, 'date' => '2026-07-14'],
            ['pelanggan_id' => 1, 'cat_id' => 6, 'jumlah' => 8, 'date' => '2026-07-15'],
            ['pelanggan_id' => 2, 'cat_id' => 1, 'jumlah' => 3, 'date' => '2026-07-02'],
            ['pelanggan_id' => 2, 'cat_id' => 7, 'jumlah' => 10, 'date' => '2026-07-06'],
            ['pelanggan_id' => 2, 'cat_id' => 9, 'jumlah' => 5, 'date' => '2026-07-09'],
            ['pelanggan_id' => 2, 'cat_id' => 4, 'jumlah' => 2, 'date' => '2026-07-13'],
            ['pelanggan_id' => 2, 'cat_id' => 11, 'jumlah' => 7, 'date' => '2026-07-15'],
            ['pelanggan_id' => 3, 'cat_id' => 6, 'jumlah' => 4, 'date' => '2026-07-04'],
            ['pelanggan_id' => 3, 'cat_id' => 8, 'jumlah' => 2, 'date' => '2026-07-07'],
            ['pelanggan_id' => 3, 'cat_id' => 10, 'jumlah' => 6, 'date' => '2026-07-11'],
            ['pelanggan_id' => 3, 'cat_id' => 3, 'jumlah' => 3, 'date' => '2026-07-14'],
            ['pelanggan_id' => 4, 'cat_id' => 2, 'jumlah' => 5, 'date' => '2026-07-03'],
            ['pelanggan_id' => 4, 'cat_id' => 5, 'jumlah' => 8, 'date' => '2026-07-08'],
            ['pelanggan_id' => 4, 'cat_id' => 9, 'jumlah' => 3, 'date' => '2026-07-12'],
            ['pelanggan_id' => 4, 'cat_id' => 1, 'jumlah' => 4, 'date' => '2026-07-15'],
            ['pelanggan_id' => 5, 'cat_id' => 7, 'jumlah' => 6, 'date' => '2026-07-05'],
            ['pelanggan_id' => 5, 'cat_id' => 11, 'jumlah' => 2, 'date' => '2026-07-10'],
            ['pelanggan_id' => 5, 'cat_id' => 4, 'jumlah' => 9, 'date' => '2026-07-14'],
            ['pelanggan_id' => 6, 'cat_id' => 3, 'jumlah' => 7, 'date' => '2026-07-06'],
            ['pelanggan_id' => 6, 'cat_id' => 8, 'jumlah' => 4, 'date' => '2026-07-11'],
            ['pelanggan_id' => 6, 'cat_id' => 10, 'jumlah' => 3, 'date' => '2026-07-15'],
            ['pelanggan_id' => 7, 'cat_id' => 1, 'jumlah' => 5, 'date' => '2026-07-07'],
            ['pelanggan_id' => 7, 'cat_id' => 6, 'jumlah' => 8, 'date' => '2026-07-12'],
            ['pelanggan_id' => 7, 'cat_id' => 9, 'jumlah' => 2, 'date' => '2026-07-15'],
            ['pelanggan_id' => 8, 'cat_id' => 2, 'jumlah' => 3, 'date' => '2026-07-08'],
            ['pelanggan_id' => 8, 'cat_id' => 7, 'jumlah' => 5, 'date' => '2026-07-13'],
            ['pelanggan_id' => 8, 'cat_id' => 11, 'jumlah' => 4, 'date' => '2026-07-15'],
            ['pelanggan_id' => 8, 'cat_id' => 4, 'jumlah' => 6, 'date' => '2026-07-09'],
            ['pelanggan_id' => 8, 'cat_id' => 5, 'jumlah' => 3, 'date' => '2026-07-14'],
        ];

        foreach ($transactions as $t) {
            $cat = $cats->firstWhere('id', $t['cat_id']);

            if (! $cat) {
                continue;
            }

            Penjualan::create([
                'pelanggan_id' => $t['pelanggan_id'],
                'cat_id' => $t['cat_id'],
                'tanggal_penjualan' => $t['date'],
                'jumlah' => $t['jumlah'],
                'harga_satuan' => $cat->harga,
                'total_harga' => $cat->harga * $t['jumlah'],
            ]);
        }

        $this->command->info('Password set for all pelanggan (password: "password").');
        $this->command->info('Created '.count($transactions).' dummy transactions.');
    }
}
