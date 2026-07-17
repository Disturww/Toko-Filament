<?php

namespace Database\Seeders;

use App\Models\Cat;
use App\Models\Merek;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@toko.com',
            'password' => bcrypt('password'),
        ]);

        Merek::insert([
            ['nama' => 'Jotun', 'negara' => 'Norwegia', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Dulux', 'negara' => 'Inggris', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Avian', 'negara' => 'Indonesia', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'No Drop', 'negara' => 'Indonesia', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Danapaint', 'negara' => 'Indonesia', 'created_at' => now(), 'updated_at' => now()],
        ]);

        Supplier::insert([
            ['nama' => 'PT Cat Indonesia', 'alamat' => 'Jl. Industri Raya No. 5, Tangerang', 'no_hp' => '0215551234', 'email' => 'info@catindonesia.co.id', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Toko Bangunan Jaya', 'alamat' => 'Jl. Raya Bogor Km 30, Jakarta Timur', 'no_hp' => '0218889999', 'email' => 'tokobangunanjaya@gmail.com', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'CV Sumber Cat', 'alamat' => 'Jl. Pahlawan No. 44, Surabaya', 'no_hp' => '0317776655', 'email' => 'sumber.cat@yahoo.com', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $merekJotun = Merek::where('nama', 'Jotun')->first();
        $merekDulux = Merek::where('nama', 'Dulux')->first();
        $merekAvian = Merek::where('nama', 'Avian')->first();
        $merekNoDrop = Merek::where('nama', 'No Drop')->first();
        $merekDanapaint = Merek::where('nama', 'Danapaint')->first();

        $supplierPT = Supplier::where('nama', 'PT Cat Indonesia')->first();
        $supplierToko = Supplier::where('nama', 'Toko Bangunan Jaya')->first();
        $supplierCV = Supplier::where('nama', 'CV Sumber Cat')->first();

        Cat::insert([
            ['nama' => 'Jotun Majestic Putih', 'warna' => 'Putih', 'harga' => 385000, 'harga_beli' => 280000, 'stok' => 50, 'satuan' => 'kaleng', 'merek_id' => $merekJotun->id, 'supplier_id' => $supplierPT->id, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Jotun Majestic Krem', 'warna' => 'Krem', 'harga' => 385000, 'harga_beli' => 275000, 'stok' => 30, 'satuan' => 'kaleng', 'merek_id' => $merekJotun->id, 'supplier_id' => $supplierPT->id, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Jotun Majestic Light Blue', 'warna' => 'Biru Muda', 'harga' => 395000, 'harga_beli' => 285000, 'stok' => 25, 'satuan' => 'kaleng', 'merek_id' => $merekJotun->id, 'supplier_id' => $supplierPT->id, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Dulux Weathershield Putih', 'warna' => 'Putih', 'harga' => 350000, 'harga_beli' => 250000, 'stok' => 40, 'satuan' => 'kaleng', 'merek_id' => $merekDulux->id, 'supplier_id' => $supplierPT->id, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Dulux Weathershield Abu', 'warna' => 'Abu-abu', 'harga' => 355000, 'harga_beli' => 255000, 'stok' => 20, 'satuan' => 'kaleng', 'merek_id' => $merekDulux->id, 'supplier_id' => $supplierToko->id, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Dulux Pentalite Putih', 'warna' => 'Putih', 'harga' => 280000, 'harga_beli' => 195000, 'stok' => 60, 'satuan' => 'kaleng', 'merek_id' => $merekDulux->id, 'supplier_id' => $supplierToko->id, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Avian Cat Putih', 'warna' => 'Putih', 'harga' => 185000, 'harga_beli' => 120000, 'stok' => 80, 'satuan' => 'kaleng', 'merek_id' => $merekAvian->id, 'supplier_id' => $supplierToko->id, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Avian Cat Cream', 'warna' => 'Krem', 'harga' => 190000, 'harga_beli' => 125000, 'stok' => 45, 'satuan' => 'kaleng', 'merek_id' => $merekAvian->id, 'supplier_id' => $supplierToko->id, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'No Drop Pelindung Putih', 'warna' => 'Putih', 'harga' => 165000, 'harga_beli' => 105000, 'stok' => 70, 'satuan' => 'kaleng', 'merek_id' => $merekNoDrop->id, 'supplier_id' => $supplierToko->id, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'No Drop Cat Tembok Putih', 'warna' => 'Putih', 'harga' => 155000, 'harga_beli' => 95000, 'stok' => 55, 'satuan' => 'kaleng', 'merek_id' => $merekNoDrop->id, 'supplier_id' => $supplierCV->id, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Danapaint Vinilex Putih', 'warna' => 'Putih', 'harga' => 210000, 'harga_beli' => 145000, 'stok' => 35, 'satuan' => 'kaleng', 'merek_id' => $merekDanapaint->id, 'supplier_id' => $supplierCV->id, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Danapaint Vinilex Pink', 'warna' => 'Pink', 'harga' => 215000, 'harga_beli' => 150000, 'stok' => 15, 'satuan' => 'kaleng', 'merek_id' => $merekDanapaint->id, 'supplier_id' => $supplierCV->id, 'created_at' => now(), 'updated_at' => now()],
        ]);

        Pelanggan::insert([
            ['nama' => 'Budi Santoso', 'alamat' => 'Jl. Merdeka No. 10, Jakarta Selatan', 'no_hp' => '081234567890', 'email' => 'budi.santoso@gmail.com', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Siti Rahayu', 'alamat' => 'Jl. Sudirman No. 25, Bandung', 'no_hp' => '082345678901', 'email' => 'siti.rahayu@gmail.com', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Andi Wijaya', 'alamat' => 'Jl. Gatot Subroto No. 5, Surabaya', 'no_hp' => '083456789012', 'email' => 'andi.wijaya@yahoo.com', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Dewi Lestari', 'alamat' => 'Jl. Thamrin No. 12, Yogyakarta', 'no_hp' => '084567890123', 'email' => 'dewi.lestari@outlook.com', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Rudi Hartono', 'alamat' => 'Jl. Asia Afrika No. 8, Semarang', 'no_hp' => '085678901234', 'email' => 'rudi.hartono@gmail.com', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Rina Susanti', 'alamat' => 'Jl. Pemuda No. 33, Medan', 'no_hp' => '086789012345', 'email' => 'rina.susanti@gmail.com', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Hendra Kusuma', 'alamat' => 'Jl. Diponegoro No. 17, Malang', 'no_hp' => '087890123456', 'email' => 'hendra.kusuma@yahoo.com', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Putri Anggraeni', 'alamat' => 'Jl. Imam Bonjol No. 21, Denpasar', 'no_hp' => '088901234567', 'email' => 'putri.anggraeni@gmail.com', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $cats = Cat::all();
        $pelanggans = Pelanggan::all();

        Penjualan::insert([
            ['pelanggan_id' => $pelanggans[0]->id, 'cat_id' => $cats[0]->id, 'tanggal_penjualan' => '2026-07-10', 'jumlah' => 5, 'harga_satuan' => 385000, 'total_harga' => 1925000, 'created_at' => now(), 'updated_at' => now()],
            ['pelanggan_id' => $pelanggans[0]->id, 'cat_id' => $cats[6]->id, 'tanggal_penjualan' => '2026-07-10', 'jumlah' => 10, 'harga_satuan' => 185000, 'total_harga' => 1850000, 'created_at' => now(), 'updated_at' => now()],
            ['pelanggan_id' => $pelanggans[1]->id, 'cat_id' => $cats[3]->id, 'tanggal_penjualan' => '2026-07-11', 'jumlah' => 8, 'harga_satuan' => 350000, 'total_harga' => 2800000, 'created_at' => now(), 'updated_at' => now()],
            ['pelanggan_id' => $pelanggans[2]->id, 'cat_id' => $cats[1]->id, 'tanggal_penjualan' => '2026-07-11', 'jumlah' => 3, 'harga_satuan' => 385000, 'total_harga' => 1155000, 'created_at' => now(), 'updated_at' => now()],
            ['pelanggan_id' => $pelanggans[3]->id, 'cat_id' => $cats[8]->id, 'tanggal_penjualan' => '2026-07-12', 'jumlah' => 15, 'harga_satuan' => 165000, 'total_harga' => 2475000, 'created_at' => now(), 'updated_at' => now()],
            ['pelanggan_id' => $pelanggans[4]->id, 'cat_id' => $cats[2]->id, 'tanggal_penjualan' => '2026-07-12', 'jumlah' => 2, 'harga_satuan' => 395000, 'total_harga' => 790000, 'created_at' => now(), 'updated_at' => now()],
            ['pelanggan_id' => $pelanggans[5]->id, 'cat_id' => $cats[5]->id, 'tanggal_penjualan' => '2026-07-13', 'jumlah' => 20, 'harga_satuan' => 280000, 'total_harga' => 5600000, 'created_at' => now(), 'updated_at' => now()],
            ['pelanggan_id' => $pelanggans[6]->id, 'cat_id' => $cats[10]->id, 'tanggal_penjualan' => '2026-07-13', 'jumlah' => 6, 'harga_satuan' => 210000, 'total_harga' => 1260000, 'created_at' => now(), 'updated_at' => now()],
            ['pelanggan_id' => $pelanggans[7]->id, 'cat_id' => $cats[4]->id, 'tanggal_penjualan' => '2026-07-14', 'jumlah' => 4, 'harga_satuan' => 355000, 'total_harga' => 1420000, 'created_at' => now(), 'updated_at' => now()],
            ['pelanggan_id' => $pelanggans[1]->id, 'cat_id' => $cats[9]->id, 'tanggal_penjualan' => '2026-07-14', 'jumlah' => 7, 'harga_satuan' => 155000, 'total_harga' => 1085000, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
