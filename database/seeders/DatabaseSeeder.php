<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\PembayaranSpp;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Admin (Bendahara/Kepala Sekolah)
        $admin = User::create([
            'name' => 'Admin SIMASPP',
            'email' => 'admin@simaspp.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'kelas_id' => null,
        ]);

        // 2. Buat beberapa Guru (Wali Kelas)
        $guru1 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@simaspp.com',
            'password' => Hash::make('password'),
            'role' => 'guru',
            'kelas_id' => null, // akan diupdate setelah kelas dibuat
        ]);

        $guru2 = User::create([
            'name' => 'Siti Rahayu',
            'email' => 'siti@simaspp.com',
            'password' => Hash::make('password'),
            'role' => 'guru',
            'kelas_id' => null,
        ]);

        $guru3 = User::create([
            'name' => 'Ahmad Hidayat',
            'email' => 'ahmad@simaspp.com',
            'password' => Hash::make('password'),
            'role' => 'guru',
            'kelas_id' => null,
        ]);

        // 3. Buat Kelas
        $kelas7A = Kelas::create([
            'nama_kelas' => '7A',
            'tingkat' => '7',
            'wali_kelas_id' => $guru1->id,
        ]);

        $kelas8B = Kelas::create([
            'nama_kelas' => '8B',
            'tingkat' => '8',
            'wali_kelas_id' => $guru2->id,
        ]);

        $kelas9C = Kelas::create([
            'nama_kelas' => '9C',
            'tingkat' => '9',
            'wali_kelas_id' => $guru3->id,
        ]);

        // 4. Update guru dengan kelas_id
        $guru1->update(['kelas_id' => $kelas7A->id]);
        $guru2->update(['kelas_id' => $kelas8B->id]);
        $guru3->update(['kelas_id' => $kelas9C->id]);

        // 5. Buat Siswa untuk tiap kelas (masing-masing 10 siswa)
        // Kelas 7A
        for ($i = 1; $i <= 10; $i++) {
            Siswa::create([
                'nis' => '7A' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'nama' => "Siswa 7A {$i}",
                'kelas_id' => $kelas7A->id,
                'nominal_spp' => 150000,
                'nama_orang_tua' => "Orang Tua 7A {$i}",
                'no_telepon_orang_tua' => "0812{$i}{$i}{$i}{$i}{$i}{$i}",
            ]);
        }

        // Kelas 8B
        for ($i = 1; $i <= 10; $i++) {
            Siswa::create([
                'nis' => '8B' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'nama' => "Siswa 8B {$i}",
                'kelas_id' => $kelas8B->id,
                'nominal_spp' => 175000,
                'nama_orang_tua' => "Orang Tua 8B {$i}",
                'no_telepon_orang_tua' => "0813{$i}{$i}{$i}{$i}{$i}{$i}",
            ]);
        }

        // Kelas 9C
        for ($i = 1; $i <= 10; $i++) {
            Siswa::create([
                'nis' => '9C' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'nama' => "Siswa 9C {$i}",
                'kelas_id' => $kelas9C->id,
                'nominal_spp' => 200000,
                'nama_orang_tua' => "Orang Tua 9C {$i}",
                'no_telepon_orang_tua' => "0814{$i}{$i}{$i}{$i}{$i}{$i}",
            ]);
        }

        // 6. Buat data pembayaran contoh (untuk beberapa siswa)
        // Ambil semua siswa
        $siswaList = Siswa::all();
        $bulanList = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni'];
        $guruList = User::where('role', 'guru')->get();

        foreach ($siswaList as $siswa) {
            // Setiap siswa punya 3-4 pembayaran
            $jumlahPembayaran = rand(3, 4);
            $bulanTerpilih = array_rand(array_flip($bulanList), $jumlahPembayaran);

            // Pastikan $bulanTerpilih berupa array
            if (!is_array($bulanTerpilih)) {
                $bulanTerpilih = [$bulanTerpilih];
            }

            foreach ($bulanTerpilih as $bulan) {
                $status = rand(1, 10) <= 7 ? 'lunas' : 'belum_lunas'; // 70% lunas
                if (rand(1, 10) <= 2) {
                    $status = 'cicil'; // 20% cicil
                }

                // Pilih guru yang mencatat (bisa dari guru manapun, tapi idealnya guru kelasnya)
                $guruPencatat = $guruList->random();

                PembayaranSpp::create([
                    'siswa_id' => $siswa->id,
                    'bulan' => $bulan,
                    'tahun_ajaran' => '2025/2026',
                    'jumlah_dibayar' => $status === 'lunas' ? $siswa->nominal_spp : ($status === 'cicil' ? $siswa->nominal_spp / 2 : 0),
                    'tanggal_bayar' => now()->subDays(rand(1, 30))->format('Y-m-d'),
                    'status' => $status,
                    'dicatat_oleh' => $guruPencatat->id,
                    'keterangan' => $status === 'cicil' ? 'Cicilan 50%' : null,
                ]);
            }
        }

        $this->command->info('✅ Seeder berhasil dijalankan!');
        $this->command->info('📧 Admin: admin@simaspp.com | Password: password');
        $this->command->info('📧 Guru: budi@simaspp.com | Password: password');
        $this->command->info('📧 Guru: siti@simaspp.com | Password: password');
        $this->command->info('📧 Guru: ahmad@simaspp.com | Password: password');
    }
}
