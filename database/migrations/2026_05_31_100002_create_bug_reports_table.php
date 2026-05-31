<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bug_reports', function (Blueprint $table) {
            $table->id('id_bug');
            $table->string('judul', 150);
            $table->text('deskripsi');
            $table->enum('status', ['open', 'resolved'])->default('open');
            $table->dateTime('tanggal_dilaporkan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bug_reports');
    }
};
