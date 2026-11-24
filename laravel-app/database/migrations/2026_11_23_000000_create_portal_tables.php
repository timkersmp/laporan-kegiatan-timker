<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('admin', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('nama');
            $table->string('nip')->nullable();
            $table->string('role')->default('admin_biasa');
            $table->timestamps();
        });

        Schema::create('petugas', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nip')->nullable();
            $table->string('pangkat')->nullable();
            $table->string('golongan')->nullable();
            $table->string('jabatan')->nullable();
            $table->string('peserta')->nullable();
            $table->string('lokasi')->nullable();
            $table->string('peserta_daerah')->nullable();
            $table->string('username')->nullable()->unique();
            $table->string('password')->nullable();
            $table->timestamps();
        });

        Schema::create('kegiatan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kegiatan');
            $table->date('tanggal_kegiatan');
            $table->string('lokasi_kegiatan');
            $table->timestamps();
        });

        Schema::create('penugasan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('petugas_id')->constrained('petugas')->cascadeOnDelete();
            $table->foreignId('kegiatan_id')->constrained('kegiatan')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['petugas_id', 'kegiatan_id']);
        });

        Schema::create('laporan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->nullable()->constrained('kegiatan')->nullOnDelete();
            $table->foreignId('petugas_id')->nullable()->constrained('petugas')->nullOnDelete();
            $table->text('isi_laporan')->nullable();
            $table->string('lampiran')->nullable();
            $table->string('status')->default('Menunggu Verifikasi');
            $table->text('catatan_admin')->nullable();
            $table->timestamp('dibuat_pada')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan');
        Schema::dropIfExists('penugasan');
        Schema::dropIfExists('kegiatan');
        Schema::dropIfExists('petugas');
        Schema::dropIfExists('admin');
    }
};
