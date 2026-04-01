<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Bimbingan;
use App\Models\BimbinganProgress;
use App\Models\Ujian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'mahasiswa' => User::where('role', 'mahasiswa')->count(),
            'dosen' => User::where('role', 'dosen')->count(),
            'kaprodi' => User::where('role', 'kaprodi')->count(),
            'admin' => User::where('role', 'admin')->count(),
            'active_users' => User::where('is_active', true)->count(),
        ];
        return view('admin.dashboard', compact('stats'));
    }

    public function userIndex()
    {
        $query = User::query();

        // Filter by role
        if (request('role')) {
            $query->where('role', request('role'));
        }

        // Filter by status
        if (request('status')) {
            $query->where('is_active', request('status') === 'active');
        }

        // Search
        if (request('search')) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . request('search') . '%')
                  ->orWhere('email', 'like', '%' . request('search') . '%')
                  ->orWhere('nim', 'like', '%' . request('search') . '%')
                  ->orWhere('nip', 'like', '%' . request('search') . '%');
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function userCreate()
    {
        return view('admin.users.create');
    }

    public function userStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:mahasiswa,dosen,kaprodi,admin',
            'password' => ['required', 'confirmed', Password::min(8)],
            'nim' => 'nullable|string|unique:users,nim|max:20',
            'nip' => 'nullable|string|unique:users,nip|max:20',
            'prodi' => 'nullable|string|max:100',
            'angkatan' => 'nullable|string|max:10',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
            'nim' => $request->nim,
            'nip' => $request->nip,
            'prodi' => $request->prodi,
            'angkatan' => $request->angkatan,
            'phone' => $request->phone,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', "User '{$user->name}' berhasil dibuat.");
    }

    public function userEdit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function userUpdate(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:mahasiswa,dosen,kaprodi,admin',
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'nim' => 'nullable|string|unique:users,nim,' . $user->id . '|max:20',
            'nip' => 'nullable|string|unique:users,nip,' . $user->id . '|max:20',
            'prodi' => 'nullable|string|max:100',
            'angkatan' => 'nullable|string|max:10',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'nim' => $request->nim,
            'nip' => $request->nip,
            'prodi' => $request->prodi,
            'angkatan' => $request->angkatan,
            'phone' => $request->phone,
            'is_active' => $request->boolean('is_active', true),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', "User '{$user->name}' berhasil diperbarui.");
    }

    public function userDestroy(User $user)
    {
        // Prevent self-delete
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun Anda sendiri.');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "User '{$userName}' berhasil dihapus.");
    }

    public function userToggleActive(User $user)
    {
        // Prevent self-deactivate
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menonaktifkan akun Anda sendiri.');
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "User '{$user->name}' berhasil {$status}.");
    }

    // ==================== MONITORING (VIEW ONLY) ====================

    public function mahasiswaList()
    {
        $mahasiswas = User::where('role', 'mahasiswa')
            ->addSelect([
                'bimbingans_count' => Bimbingan::selectRaw('count(distinct jenis_bimbingan)')
                    ->whereColumn('mahasiswa_id', 'users.id')
            ])
            ->withCount('ujians')
            ->orderBy('name')
            ->paginate(20);
        return view('admin.monitoring.mahasiswa.index', compact('mahasiswas'));
    }

    public function mahasiswaDetail(User $mahasiswa)
    {
        if ($mahasiswa->role !== 'mahasiswa') abort(404);
        $bimbingans = Bimbingan::where('mahasiswa_id', $mahasiswa->id)
            ->with(['dosen', 'progresses'])
            ->withCount(['progresses'])
            ->orderBy('jenis_bimbingan')->get();

        $ujians = Ujian::where('mahasiswa_id', $mahasiswa->id)
            ->with(['pembimbing1', 'pembimbing2', 'penguji1', 'penguji2', 'dokumen'])->get();
        return view('admin.monitoring.mahasiswa.detail', compact('mahasiswa', 'bimbingans', 'ujians'));
    }

    public function bimbinganList()
    {
        $query = BimbinganProgress::with(['bimbingan.mahasiswa', 'bimbingan.dosen']);

        // Filter status
        if (request('status')) {
            $query->where('status', request('status'));
        }

        // Filter jenis bimbingan
        if (request('jenis')) {
            $query->whereHas('bimbingan', function($q) {
                $q->where('jenis_bimbingan', request('jenis'));
            });
        }

        // Search mahasiswa
        if (request('search')) {
            $query->whereHas('bimbingan.mahasiswa', function($q) {
                $q->where('name', 'like', '%'.request('search').'%')
                  ->orWhere('nim', 'like', '%'.request('search').'%');
            });
        }

        $progresses = $query->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.monitoring.bimbingan.index', compact('progresses'));
    }

    public function bimbinganShow(Bimbingan $bimbingan)
    {
        $bimbingan->load(['mahasiswa', 'dosen', 'progresses']);
        return view('admin.monitoring.bimbingan.show', compact('bimbingan'));
    }

    public function ujianList()
    {
        $ujians = Ujian::with(['mahasiswa', 'pembimbing1', 'penguji1'])
            ->orderBy('status')
            ->orderBy('tanggal_ujian')
            ->paginate(15);
        return view('admin.monitoring.ujian.index', compact('ujians'));
    }

    public function ujianShow(Ujian $ujian)
    {
        $ujian->load(['mahasiswa', 'pembimbing1', 'pembimbing2', 'penguji1', 'penguji2', 'dokumen']);
        return view('admin.monitoring.ujian.show', compact('ujian'));
    }
}
