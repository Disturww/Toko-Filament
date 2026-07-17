<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pelanggans', function (Blueprint $table) {
            $table->text('alamat')->nullable()->change();
            $table->string('no_hp', 20)->nullable()->change();
            $table->string('email', 100)->nullable()->change();
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->text('alamat')->nullable()->change();
            $table->string('no_hp', 20)->nullable()->change();
            $table->string('email', 100)->nullable()->change();
        });

        Schema::table('mereks', function (Blueprint $table) {
            $table->string('nama', 100)->change();
            $table->string('negara', 100)->nullable()->change();
        });

        Schema::table('cats', function (Blueprint $table) {
            $table->string('nama', 100)->change();
            $table->string('warna', 50)->nullable()->change();
            $table->string('satuan', 50)->change();
        });

        Schema::table('penjualans', function (Blueprint $table) {
            $table->date('tanggal_penjualan')->after('cat_id')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::table('pelanggans', function (Blueprint $table) {
            $table->string('alamat')->nullable()->change();
            $table->string('no_hp')->nullable()->change();
            $table->string('email')->nullable()->change();
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('alamat')->nullable()->change();
            $table->string('no_hp')->nullable()->change();
            $table->string('email')->nullable()->change();
        });

        Schema::table('mereks', function (Blueprint $table) {
            $table->string('nama')->change();
            $table->string('negara')->nullable()->change();
        });

        Schema::table('cats', function (Blueprint $table) {
            $table->string('nama')->change();
            $table->string('warna')->nullable()->change();
            $table->string('satuan')->change();
        });

        Schema::table('penjualans', function (Blueprint $table) {
            $table->dropColumn('tanggal_penjualan');
        });
    }
};
