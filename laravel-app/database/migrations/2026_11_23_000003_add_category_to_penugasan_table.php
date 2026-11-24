<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penugasan', function (Blueprint $table) {
            $table->foreignId('kategori_penugasan_id')->nullable()->after('kegiatan_id')
                ->constrained('kategori_penugasan')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('penugasan', function (Blueprint $table) {
            $table->dropConstrainedForeignId('kategori_penugasan_id');
        });
    }
};
