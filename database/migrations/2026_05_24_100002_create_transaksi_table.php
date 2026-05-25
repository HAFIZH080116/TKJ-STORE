<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->unsignedBigInteger('id_user');
            $table->dateTime('tanggal_transaksi');
            $table->decimal('total_pembayaran', 15, 2);
            $table->string('metode_pembayaran', 50);
            $table->enum('status', ['pending', 'diproses', 'selesai'])->default('pending');

            $table->foreign('id_user')
                ->references('id_user')
                ->on('users')
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
