<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Bimbingan;
use App\Models\BimbinganProgress;
use App\Models\Ujian;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::create([
            'name'      => 'Administrator',
            'email'     => 'admin@biostrack.test',
            'password'  => Hash::make('password'),
            'role'      => 'admin',
            'phone'     => '081234560000',
            'is_active' => true,
        ]);

        // Kaprodi
        $kaprodi = User::create([
            'name'      => 'Prof. Dr. Ahmad Kaprodi, M.T.',
            'email'     => 'kaprodi@biostrack.test',
            'password'  => Hash::make('password'),
            'role'      => 'kaprodi',
            'nip'       => '198001012005011001',
            'prodi'     => 'Teknik Informatika',
            'phone'     => '081234560001',
            'is_active' => true,
        ]);

        // Dosen
        $dosen = [];
        $dosenData = [
            ['Dr. Budi Santoso, M.Kom.', 'budi@biostrack.test', '198502152010011002'],
            ['Dr. Citra Dewi, M.T.',     'citra@biostrack.test', '197808202009012003'],
            ['Hendra Wijaya, M.Kom.',    'hendra@biostrack.test', '199001052015011004'],
            ['Siti Rahayu, M.T.',        'siti@biostrack.test', '198807102012012005'],
        ];
        foreach ($dosenData as [$name, $email, $nip]) {
            $dosen[] = User::create([
                'name'      => $name,
                'email'     => $email,
                'password'  => Hash::make('password'),
                'role'      => 'dosen',
                'nip'       => $nip,
                'prodi'     => 'Teknik Informatika',
                'phone'     => '0812345' . rand(10000, 99999),
                'is_active' => true,
            ]);
        }

        // Mahasiswa
        $mahasiswaData = [
            ['Andi Pratama',      'andi@biostrack.test',  '2021001', '2021'],
            ['Bela Sari',         'bela@biostrack.test',  '2021002', '2021'],
            ['Cahyo Nugroho',     'cahyo@biostrack.test', '2021003', '2021'],
            ['Dewi Anggraeni',    'dewi@biostrack.test',  '2021004', '2021'],
            ['Erik Setiawan',     'erik@biostrack.test',  '2020001', '2020'],
            ['Fira Nanda',        'fira@biostrack.test',  '2020002', '2020'],
            ['Galih Prabowo',     'galih@biostrack.test', '2020003', '2020'],
        ];
        $mahasiswas = [];
        foreach ($mahasiswaData as [$name, $email, $nim, $angkatan]) {
            $mahasiswas[] = User::create([
                'name'      => $name,
                'email'     => $email,
                'password'  => Hash::make('password'),
                'role'      => 'mahasiswa',
                'nim'       => $nim,
                'prodi'     => 'Teknik Informatika',
                'angkatan'  => $angkatan,
                'phone'     => '0815' . rand(1000000, 9999999),
                'is_active' => true,
            ]);
        }

        // Sample: Andi has bimbingan proposal (selesai) + seminar_hasil (in progress)
        $bimbingan1 = Bimbingan::create([
            'mahasiswa_id'     => $mahasiswas[0]->id,
            'dosen_id'         => $dosen[0]->id,
            'jenis_bimbingan'  => 'proposal',
            'tanggal_bimbingan'=> now()->subDays(60),
            'topik'            => 'Implementasi Machine Learning untuk Deteksi Anomali Jaringan',
            'catatan_mahasiswa'=> 'Bimbingan pertama proposal',
            'status'           => 'selesai',
        ]);
        BimbinganProgress::create([
            'bimbingan_id' => $bimbingan1->id,
            'catatan'      => 'Revisi BAB 1 latar belakang sudah selesai',
            'status'       => 'disetujui',
        ]);

        $bimbingan2 = Bimbingan::create([
            'mahasiswa_id'     => $mahasiswas[0]->id,
            'dosen_id'         => $dosen[0]->id,
            'jenis_bimbingan'  => 'seminar_hasil',
            'tanggal_bimbingan'=> now()->addDays(7),
            'topik'            => 'Seminar Hasil Penelitian - Deteksi Anomali Jaringan',
            'catatan_mahasiswa'=> 'Berencana seminar akhir bulan',
            'status'           => 'disetujui',
        ]);
        BimbinganProgress::create([
            'bimbingan_id' => $bimbingan2->id,
            'catatan'      => 'BAB 4 hasil penelitian sudah dilengkapi dengan grafik performa',
            'status'       => 'menunggu',
        ]);

        // Sample ujian for Andi (proposal selesai, jadi boleh buat ujian proposal)
        Ujian::create([
            'mahasiswa_id'         => $mahasiswas[0]->id,
            'jenis_ujian'          => 'proposal',
            'tanggal_ujian'        => now()->addDays(14),
            'tempat_ujian'         => 'Ruang Sidang A',
            'dosen_pembimbing1_id' => $dosen[0]->id,
            'dosen_penguji1_id'    => $dosen[1]->id,
            'dosen_penguji2_id'    => $dosen[2]->id,
            'status'               => 'disetujui_dosen',
            'status_pembimbing1'   => 'disetujui',
            'status_penguji1'      => 'disetujui',
            'status_penguji2'      => 'disetujui',
            'status_kaprodi'       => 'menunggu',
        ]);

        // Bela has a pending bimbingan proposal
        Bimbingan::create([
            'mahasiswa_id'     => $mahasiswas[1]->id,
            'dosen_id'         => $dosen[1]->id,
            'jenis_bimbingan'  => 'proposal',
            'tanggal_bimbingan'=> now()->addDays(3),
            'topik'            => 'Analisis Sentimen Media Sosial menggunakan LSTM',
            'catatan_mahasiswa'=> 'Mohon bimbingan BAB 1-3',
            'status'           => 'menunggu',
        ]);

        $this->command->info('Database seeded successfully!');
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['Admin', 'admin@biostrack.test', 'password'],
                ['Kaprodi', 'kaprodi@biostrack.test', 'password'],
                ['Dosen 1', 'budi@biostrack.test', 'password'],
                ['Dosen 2', 'citra@biostrack.test', 'password'],
                ['Mahasiswa 1', 'andi@biostrack.test', 'password'],
                ['Mahasiswa 2', 'bela@biostrack.test', 'password'],
            ]
        );
    }
}
