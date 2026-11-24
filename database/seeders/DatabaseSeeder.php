<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Kegiatan;
use App\Models\Laporan;
use App\Models\Petugas;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Admin::firstOrCreate(['username' => 'admin'], [
            'nama' => 'Administrator', 'role' => 'admin_biasa', 'password' => Hash::make('admin123'),
        ]);
        Admin::firstOrCreate(['username' => 'verifikator'], [
            'nama' => 'Karyawan', 'role' => 'verifikator', 'password' => Hash::make('admin123'),
        ]);

        $petugasList = [
            ['nama' => 'Siti Rahmawati', 'nip' => '198812342019032001', 'jabatan' => 'Staf Administrasi', 'username' => 'siti.rahmawati'],
            ['nama' => 'Ahmad Fauzi', 'nip' => '199002152020011002', 'jabatan' => 'Pengelola Data', 'username' => 'ahmad.fauzi'],
            ['nama' => 'Dewi Anggraini', 'nip' => '199205102021012003', 'jabatan' => 'Staf Pengumpulan Laporan', 'username' => 'dewi.anggraini'],
            ['nama' => 'Rizky Pratama', 'nip' => '199407212022031004', 'jabatan' => 'Verifikator Lapangan', 'username' => 'rizky.pratama'],
            ['nama' => 'Nur Aisyah', 'nip' => '199611052023012005', 'jabatan' => 'Staf Umum', 'username' => 'nur.aisyah'],
        ];

        $petugas = [];
        foreach ($petugasList as $data) {
            $petugas[] = Petugas::firstOrCreate(
                ['username' => $data['username']],
                $data + ['password' => Hash::make('admin123')]
            );
        }

        $kegiatanList = [
            ['nama_kegiatan' => 'Sosialisasi Kurikulum Merdeka', 'tanggal_kegiatan' => '2026-11-10', 'lokasi_kegiatan' => 'Bandung'],
            ['nama_kegiatan' => 'Monitoring Implementasi Kurikulum', 'tanggal_kegiatan' => '2026-11-17', 'lokasi_kegiatan' => 'Garut'],
            ['nama_kegiatan' => 'Evaluasi Laporan Kegiatan Sekolah', 'tanggal_kegiatan' => '2026-11-21', 'lokasi_kegiatan' => 'Cimahi'],
        ];

        $kegiatan = [];
        foreach ($kegiatanList as $data) {
            $kegiatan[] = Kegiatan::firstOrCreate($data);
        }

        foreach ($petugas as $index => $person) {
            $person->kegiatan()->syncWithoutDetaching([
                $kegiatan[$index % count($kegiatan)]->id,
                $kegiatan[($index + 1) % count($kegiatan)]->id,
            ]);
        }

        Laporan::firstOrCreate([
            'kegiatan_id' => $kegiatan[0]->id,
            'petugas_id' => $petugas[0]->id,
            'isi_laporan' => 'Sosialisasi berjalan lancar. Hadir 45 peserta dari berbagai sekolah di wilayah Bandung.',
        ], [
            'status' => 'Menunggu Verifikasi',
            'dibuat_pada' => now(),
        ]);

        Laporan::firstOrCreate([
            'kegiatan_id' => $kegiatan[1]->id,
            'petugas_id' => $petugas[1]->id,
            'isi_laporan' => 'Monitoring lapangan selesai. Temuan dan rekomendasi sudah dicatat pada formulir kegiatan.',
        ], [
            'status' => 'Disetujui',
            'catatan_admin' => 'Laporan sudah diverifikasi.',
            'dibuat_pada' => now(),
        ]);
    }
}
