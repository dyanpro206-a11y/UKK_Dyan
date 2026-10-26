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
            $table->bigIncrements('transaksi_id');
            $table->unsignedBigInteger('peminjaman_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('jenis_transaksi', ['Peminjaman', 'Pengembalian']);
            $table->date('tgl_transaksi');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('peminjaman_id')
                ->references('peminjaman_id')
                ->on('peminjaman')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
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
