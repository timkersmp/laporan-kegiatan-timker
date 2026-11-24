<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Kegiatan;
use App\Models\Laporan;
use App\Models\Petugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PortalController extends Controller
{
    public function loginForm(Request $request)
    {
        if ($request->session()->has('user_id')) {
            return redirect()->route(match ($request->session()->get('role')) {
                'verifikator' => 'reports.index',
                'petugas' => 'employee.dashboard',
                default => 'dashboard',
            });
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $input = $request->validate([
            'role' => ['required', Rule::in(['admin', 'karyawan'])],
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if ($input['role'] === 'admin') {
            $admin = Admin::where('username', $input['username'])->where('role', 'admin_biasa')->first();
            if ($admin && ($admin->status ?? 'aktif') === 'aktif' && $this->passwordMatches($input['password'], $admin->password)) {
                $request->session()->regenerate();
                $request->session()->put(['user_id' => $admin->id, 'role' => 'admin_biasa', 'name' => $admin->nama, 'username' => $admin->username]);
                return redirect()->route('dashboard');
            }
        } else {
            $karyawan = Admin::where('username', $input['username'])->where('role', 'verifikator')->first();
            if ($karyawan && ($karyawan->status ?? 'aktif') === 'aktif' && $this->passwordMatches($input['password'], $karyawan->password)) {
                // Accounts created under the former "verifikator" role now use the Karyawan workspace.
                $petugas = Petugas::firstOrNew(['username' => $karyawan->username]);
                $petugas->fill([
                    'nama' => $karyawan->nama,
                    'nip' => $karyawan->nip,
                    'password' => $karyawan->password,
                ]);
                $petugas->save();
                $request->session()->regenerate();
                $request->session()->put(['user_id' => $petugas->id, 'role' => 'petugas', 'name' => $petugas->nama, 'username' => $petugas->username]);
                return redirect()->route('employee.dashboard');
            }
            $petugas = Petugas::where('username', $input['username'])->first();
            if ($petugas && $this->passwordMatches($input['password'], $petugas->password)) {
                $request->session()->regenerate();
                $request->session()->put(['user_id' => $petugas->id, 'role' => 'petugas', 'name' => $petugas->nama, 'username' => $petugas->username]);
                return redirect()->route('employee.dashboard');
            }
        }

        return back()->withInput($request->only('username', 'role'))->with('error', 'Username, password, atau peran tidak sesuai.');
    }

    private function passwordMatches(string $plain, ?string $stored): bool
    {
        if (!$stored) return false;
        return (str_starts_with($stored, '$2y$') || str_starts_with($stored, '$argon2'))
            ? Hash::check($plain, $stored)
            : hash_equals($stored, $plain);
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function dashboard(Request $request)
    {
        if ($request->session()->get('role') === 'petugas') return redirect()->route('employee.dashboard');
        return view('dashboard', [
            'totalPetugas' => Petugas::count(), 'totalKegiatan' => Kegiatan::count(),
            'totalLaporan' => Laporan::count(), 'totalMenunggu' => Laporan::whereIn('status', ['Menunggu Verifikasi', 'menunggu_verifikasi', 'Belum Diverifikasi'])->count(),
        ]);
    }

    public function accounts(Request $request)
    {
        $editAccount = $request->filled('edit') ? Admin::find($request->integer('edit')) : null;
        return view('accounts.index', ['accounts' => Admin::orderBy('nama')->get(), 'editAccount' => $editAccount]);
    }

    public function storeAccount(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'nip' => ['nullable', 'string', 'max:40'],
            'username' => ['required', 'string', 'max:100', 'unique:admin,username', 'unique:petugas,username'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', Rule::in(['admin_biasa', 'verifikator'])],
        ]);
        $data['password'] = Hash::make($data['password']);
        $data['status'] = 'aktif';
        Admin::create($data);
        return redirect()->route('accounts.index')->with('success', 'Akun berhasil dibuat.');
    }

    public function updateAccount(Request $request, Admin $admin)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'nip' => ['nullable', 'string', 'max:40'],
            'username' => ['required', 'string', 'max:100', Rule::unique('admin', 'username')->ignore($admin->id), Rule::unique('petugas', 'username')],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['required', Rule::in(['admin_biasa', 'verifikator'])],
        ]);
        if ($admin->id === (int) $request->session()->get('user_id') && $data['role'] !== 'admin_biasa') {
            return back()->withErrors(['role' => 'Peran akun yang sedang digunakan tidak dapat diubah.']);
        }
        if ($admin->role === 'admin_biasa' && ($admin->status ?? 'aktif') === 'aktif' && $data['role'] !== 'admin_biasa' && Admin::where('role', 'admin_biasa')->where('status', 'aktif')->count() <= 1) {
            return back()->withErrors(['role' => 'Setidaknya harus ada satu akun admin aktif.']);
        }
        if (!empty($data['password'])) $data['password'] = Hash::make($data['password']); else unset($data['password']);
        $admin->update($data);
        if ($admin->id === (int) $request->session()->get('user_id')) {
            $request->session()->put(['name' => $admin->nama, 'username' => $admin->username]);
        }
        return redirect()->route('accounts.index')->with('success', 'Akun berhasil diperbarui.');
    }

    public function updateAccountStatus(Request $request, Admin $admin)
    {
        $data = $request->validate(['status' => ['required', Rule::in(['aktif', 'nonaktif'])]]);
        if ($admin->id === (int) $request->session()->get('user_id') && $data['status'] !== 'aktif') {
            return back()->withErrors(['status' => 'Akun yang sedang digunakan tidak dapat dinonaktifkan.']);
        }
        if ($admin->role === 'admin_biasa' && ($admin->status ?? 'aktif') === 'aktif' && $data['status'] !== 'aktif' && Admin::where('role', 'admin_biasa')->where('status', 'aktif')->count() <= 1) {
            return back()->withErrors(['status' => 'Setidaknya harus ada satu akun admin aktif.']);
        }
        $admin->update($data);
        return back()->with('success', 'Status akun berhasil diperbarui.');
    }

    public function deleteAccount(Request $request, Admin $admin)
    {
        if ($admin->id === (int) $request->session()->get('user_id')) {
            return back()->withErrors(['akun' => 'Akun yang sedang digunakan tidak dapat dihapus.']);
        }
        if ($admin->role === 'admin_biasa' && ($admin->status ?? 'aktif') === 'aktif' && Admin::where('role', 'admin_biasa')->where('status', 'aktif')->count() <= 1) {
            return back()->withErrors(['akun' => 'Setidaknya harus ada satu akun admin aktif.']);
        }
        $admin->delete();
        return redirect()->route('accounts.index')->with('success', 'Akun berhasil dihapus.');
    }

    public function staff()
    {
        return view('staff.index', ['petugas' => Petugas::orderBy('nama')->get()]);
    }

    public function storeStaff(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255'], 'nip' => ['required', 'string', 'max:40'],
            'jabatan' => ['required', 'string', 'max:150'],
            'username' => ['required', 'string', 'max:100', 'unique:petugas,username'],
            'password' => ['required', 'string', 'min:6'],
        ]);
        $data['password'] = Hash::make($data['password']);
        Petugas::create($data);
        return back()->with('success', 'Data petugas berhasil ditambahkan.');
    }

    public function deleteStaff(Petugas $petugas)
    {
        if ($petugas->laporan()->exists()) return back()->with('error', 'Karyawan yang sudah memiliki laporan tidak dapat dihapus.');
        $petugas->kegiatan()->detach();
        $petugas->delete();
        return back()->with('success', 'Data karyawan berhasil dihapus.');
    }

    public function reports()
    {
        return view('reports.index', [
            'laporan' => Laporan::with(['petugas', 'kegiatan'])
                ->whereIn('status', ['Menunggu Verifikasi', 'menunggu_verifikasi', 'Belum Diverifikasi'])
                ->latest('dibuat_pada')->get(),
        ]);
    }

    public function reportArchive()
    {
        return view('reports.archive', [
            'laporan' => Laporan::with(['petugas', 'kegiatan'])
                ->whereIn('status', ['Lengkap', 'disetujui'])
                ->latest('dibuat_pada')->get(),
        ]);
    }

    public function editReport(Laporan $laporan)
    {
        $laporan->load(['petugas', 'kegiatan']);
        return view('reports.edit', ['laporan' => $laporan, 'karyawan' => Petugas::orderBy('nama')->get()]);
    }

    public function updateReport(Request $request, Laporan $laporan)
    {
        $data = $request->validate([
            'nama_kegiatan' => ['required', 'string', 'max:255'],
            'tanggal_kegiatan' => ['required', 'date'],
            'lokasi_kegiatan' => ['required', 'string', 'max:255'],
            'petugas_id' => ['required', 'integer', 'exists:petugas,id'],
            'isi_laporan' => ['required', 'string', 'min:10'],
            'status' => ['required', Rule::in(['Menunggu Verifikasi', 'Lengkap', 'Perlu Diperbaiki', 'Ditolak', 'disetujui', 'perlu_diperbaiki'])],
            'catatan_admin' => [Rule::requiredIf(in_array($request->input('status'), ['Perlu Diperbaiki', 'perlu_diperbaiki', 'Ditolak'], true)), 'nullable', 'string', 'max:5000'],
            'lampiran_file' => ['nullable', 'file', 'max:10240', 'mimes:pdf,jpg,jpeg,png,doc,docx'],
            'hapus_lampiran' => ['nullable', 'boolean'],
        ]);

        $oldAttachment = $laporan->lampiran;
        $newAttachment = $request->file('lampiran_file')?->store('lampiran', 'public');
        $data['lampiran'] = $newAttachment ?? ($request->boolean('hapus_lampiran') ? null : $oldAttachment);

        DB::transaction(function () use ($data, $laporan) {
            $kegiatanData = [
                'nama_kegiatan' => $data['nama_kegiatan'],
                'tanggal_kegiatan' => $data['tanggal_kegiatan'],
                'lokasi_kegiatan' => $data['lokasi_kegiatan'],
            ];
            if ($laporan->kegiatan && !$laporan->kegiatan->laporan()->where('id', '<>', $laporan->id)->exists()) {
                $laporan->kegiatan->update($kegiatanData);
            } else {
                $kegiatan = Kegiatan::create($kegiatanData);
                $laporan->kegiatan_id = $kegiatan->id;
            }
            $laporan->update([
                'petugas_id' => $data['petugas_id'],
                'isi_laporan' => $data['isi_laporan'],
                'status' => $data['status'],
                'catatan_admin' => $data['catatan_admin'] ?? null,
                'lampiran' => $data['lampiran'],
            ]);
        });

        if ($oldAttachment && $oldAttachment !== $data['lampiran']) Storage::disk('public')->delete($oldAttachment);
        return redirect()->route('reports.archive')->with('success', 'Data laporan berhasil diperbarui.');
    }

    public function deleteReport(Laporan $laporan)
    {
        $attachment = $laporan->lampiran;
        $kegiatanId = $laporan->kegiatan_id;
        DB::transaction(function () use ($laporan, $kegiatanId) {
            $laporan->delete();
            if ($kegiatanId && !Laporan::where('kegiatan_id', $kegiatanId)->exists()
                && !DB::table('penugasan')->where('kegiatan_id', $kegiatanId)->exists()) {
                Kegiatan::whereKey($kegiatanId)->delete();
            }
        });
        if ($attachment) Storage::disk('public')->delete($attachment);
        return redirect()->route('reports.archive')->with('success', 'Laporan berhasil dihapus.');
    }

    public function attachment(Request $request, Laporan $laporan)
    {
        abort_unless($laporan->lampiran, 404);
        if ($request->session()->get('role') === 'petugas') {
            abort_unless((int) $laporan->petugas_id === (int) $request->session()->get('user_id'), 403);
        }
        $disk = Storage::disk('public');
        abort_unless($disk->exists($laporan->lampiran), 404);
        return $disk->response($laporan->lampiran, basename($laporan->lampiran), ['X-Content-Type-Options' => 'nosniff'], 'inline');
    }

    public function review(Request $request, Laporan $laporan)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['Lengkap', 'Perlu Diperbaiki', 'Ditolak', 'disetujui', 'perlu_diperbaiki'])],
            'catatan_admin' => [Rule::requiredIf(in_array($request->input('status'), ['Perlu Diperbaiki', 'perlu_diperbaiki', 'Ditolak'], true)), 'nullable', 'string', 'max:5000'],
        ]);
        $laporan->update($data);
        return back()->with('success', 'Status laporan berhasil diperbarui.');
    }

    public function rejectReport(Request $request, Laporan $laporan)
    {
        $data = $request->validate(
            ['catatan_admin' => ['required', 'string', 'max:5000']],
            ['catatan_admin.required' => 'Tuliskan catatan agar karyawan tahu bagian yang perlu dilengkapi.']
        );
        $laporan->update(['status' => 'Ditolak', 'catatan_admin' => $data['catatan_admin']]);
        return redirect()->route('reports.index')->with('success', 'Laporan ditolak dan dikembalikan kepada karyawan.');
    }

    public function employeeDashboard(Request $request)
    {
        $petugas = Petugas::findOrFail($request->session()->get('user_id'));
        $laporan = Laporan::with('kegiatan')->where('petugas_id', $petugas->id)->latest('dibuat_pada')->get();
        return view('employee.dashboard', [
            'petugas' => $petugas,
            'totalKegiatan' => $laporan->count(),
            'totalLaporan' => $laporan->count(),
            'menungguVerifikasi' => $laporan->whereIn('status', ['Menunggu Verifikasi', 'menunggu_verifikasi', 'Belum Diverifikasi'])->count(),
            'laporanTerbaru' => $laporan->take(5),
        ]);
    }

    public function completedActivities(Request $request)
    {
        $petugas = Petugas::findOrFail($request->session()->get('user_id'));
        $laporan = Laporan::with('kegiatan')->where('petugas_id', $petugas->id)->latest('dibuat_pada')->get();
        return view('employee.completed', compact('petugas', 'laporan'));
    }

    public function editOwnReport(Request $request, Laporan $laporan)
    {
        abort_unless((int) $laporan->petugas_id === (int) $request->session()->get('user_id'), 403);
        return view('employee.edit-report', ['laporan' => $laporan->load('kegiatan')]);
    }

    public function updateOwnReport(Request $request, Laporan $laporan)
    {
        abort_unless((int) $laporan->petugas_id === (int) $request->session()->get('user_id'), 403);
        $data = $request->validate([
            'nama_kegiatan' => ['required', 'string', 'max:255'],
            'tanggal_kegiatan' => ['required', 'date'],
            'lokasi_kegiatan' => ['required', 'string', 'max:255'],
            'isi_laporan' => ['required', 'string', 'min:10'],
            'lampiran_file' => ['nullable', 'file', 'max:10240', 'mimes:pdf,jpg,jpeg,png,doc,docx'],
            'hapus_lampiran' => ['nullable', 'boolean'],
        ]);
        $oldAttachment = $laporan->lampiran;
        $newAttachment = $request->file('lampiran_file')?->store('lampiran', 'public');
        $attachment = $newAttachment ?? ($request->boolean('hapus_lampiran') ? null : $oldAttachment);
        DB::transaction(function () use ($laporan, $data, $attachment) {
            $kegiatan = $laporan->kegiatan;
            if ($kegiatan && !$kegiatan->laporan()->where('id', '<>', $laporan->id)->exists()) {
                $kegiatan->update([
                    'nama_kegiatan' => $data['nama_kegiatan'],
                    'tanggal_kegiatan' => $data['tanggal_kegiatan'],
                    'lokasi_kegiatan' => $data['lokasi_kegiatan'],
                ]);
            } else {
                $kegiatan = Kegiatan::create([
                    'nama_kegiatan' => $data['nama_kegiatan'],
                    'tanggal_kegiatan' => $data['tanggal_kegiatan'],
                    'lokasi_kegiatan' => $data['lokasi_kegiatan'],
                ]);
                $laporan->kegiatan_id = $kegiatan->id;
            }
            $laporan->update([
                'isi_laporan' => $data['isi_laporan'],
                'lampiran' => $attachment,
                'status' => 'Menunggu Verifikasi',
                'catatan_admin' => null,
            ]);
        });
        if ($oldAttachment && $oldAttachment !== $attachment) Storage::disk('public')->delete($oldAttachment);
        return redirect()->route('employee.completed')->with('success', 'Laporan berhasil diperbarui dan dikirim kembali untuk verifikasi.');
    }

    public function deleteOwnReport(Request $request, Laporan $laporan)
    {
        abort_unless((int) $laporan->petugas_id === (int) $request->session()->get('user_id'), 403);
        $attachment = $laporan->lampiran;
        $kegiatanId = $laporan->kegiatan_id;
        DB::transaction(function () use ($laporan, $kegiatanId) {
            $laporan->delete();
            if ($kegiatanId && !Laporan::where('kegiatan_id', $kegiatanId)->exists()
                && !DB::table('penugasan')->where('kegiatan_id', $kegiatanId)->exists()) {
                Kegiatan::whereKey($kegiatanId)->delete();
            }
        });
        if ($attachment) Storage::disk('public')->delete($attachment);
        return redirect()->route('employee.completed')->with('success', 'Laporan berhasil dihapus.');
    }

    public function reportForm(Request $request)
    {
        $petugas = Petugas::findOrFail($request->session()->get('user_id'));
        return view('employee.report', compact('petugas'));
    }

    public function myActivities()
    {
        return redirect()->route('employee.dashboard');
    }

    public function submitReport(Request $request)
    {
        $data = $request->validate([
            'nama_kegiatan' => ['required', 'string', 'max:255'],
            'tanggal_kegiatan' => ['required', 'date'],
            'lokasi_kegiatan' => ['required', 'string', 'max:255'],
            'isi_laporan' => ['required', 'string', 'min:10'],
            'lampiran_file' => ['nullable', 'file', 'max:10240', 'mimes:pdf,jpg,jpeg,png,doc,docx'],
        ], [
            'nama_kegiatan.required' => 'Nama kegiatannya belum diisi.',
            'tanggal_kegiatan.required' => 'Tanggal kegiatannya belum dipilih.',
            'lokasi_kegiatan.required' => 'Lokasi kegiatannya belum diisi.',
            'isi_laporan.required' => 'Isi laporannya belum diisi.',
            'isi_laporan.min' => 'Isi laporan minimal 10 karakter, ya.',
            'lampiran_file.max' => 'Ukuran lampiran maksimal 10 MB.',
            'lampiran_file.mimes' => 'Jenis lampiran yang didukung: PDF, JPG, PNG, DOC, atau DOCX.',
        ]);
        $petugasId = $request->session()->get('user_id');
        $path = $request->file('lampiran_file')?->store('lampiran', 'public');
        DB::transaction(function () use ($data, $petugasId, $path) {
            $kegiatan = Kegiatan::create([
                'nama_kegiatan' => $data['nama_kegiatan'],
                'tanggal_kegiatan' => $data['tanggal_kegiatan'],
                'lokasi_kegiatan' => $data['lokasi_kegiatan'],
            ]);
            Laporan::create([
                'kegiatan_id' => $kegiatan->id,
                'petugas_id' => $petugasId,
                'isi_laporan' => $data['isi_laporan'],
                'lampiran' => $path,
                'status' => 'Menunggu Verifikasi',
                'dibuat_pada' => now(),
            ]);
        });
        return back()->with('success', 'Laporan berhasil dikirim.');
    }
}
