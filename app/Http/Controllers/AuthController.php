<?php

namespace App\Http\Controllers;

use App\Models\Ujian;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user()->role);
        }
        // Get approved ujians for portal display (exclude those with uploaded dokumen)
        // Only show next 50 upcoming exams to avoid memory exhaustion
        $ujians = Ujian::where('status', 'disetujui_kaprodi')
            ->whereDoesntHave('dokumen')
            ->select('id', 'mahasiswa_id', 'dosen_pembimbing1_id', 'dosen_pembimbing2_id', 
                     'dosen_penguji1_id', 'dosen_penguji2_id', 'jenis_ujian', 'tanggal_ujian', 'tempat_ujian')
            ->with(['mahasiswa:id,name,nim,prodi', 
                    'pembimbing1:id,name', 
                    'pembimbing2:id,name', 
                    'penguji1:id,name', 
                    'penguji2:id,name'])
            ->orderBy('tanggal_ujian', 'asc')
            ->limit(50)
            ->get();
        return view('auth.login', compact('ujians'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();
            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Akun Anda tidak aktif. Hubungi administrator.']);
            }
            return $this->redirectByRole($user->role);
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'nim' => 'required|string|unique:users,nim|max:20',
            'prodi' => 'required|string|max:100',
            'angkatan' => 'required|string|max:10',
            'phone' => 'nullable|string|max:20',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'nim' => $request->nim,
            'prodi' => $request->prodi,
            'angkatan' => $request->angkatan,
            'phone' => $request->phone,
            'role' => 'mahasiswa',
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);
        return redirect()->route('mahasiswa.dashboard')->with('success', 'Akun berhasil dibuat. Selamat datang!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function showProfile()
    {
        return view('auth.profile', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'signature' => 'nullable|file|image|max:2048',
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ]);

        $data = ['name' => $request->name, 'phone' => $request->phone];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('signature')) {
            $path = $request->file('signature')->store('signatures', 'public');
            $data['signature_path'] = $path;
        }

        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    private function redirectByRole(string $role)
    {
        return match($role) {
            'mahasiswa' => redirect()->route('mahasiswa.dashboard'),
            'dosen' => redirect()->route('dosen.dashboard'),
            'kaprodi' => redirect()->route('kaprodi.dashboard'),
            'admin' => redirect()->route('admin.dashboard'),
            default => redirect('/'),
        };
    }
}
