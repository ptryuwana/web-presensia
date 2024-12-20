<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class PegawaiSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID'); // Menggunakan locale Indonesia

        // Insert HRD
        $hrdId = DB::table('pegawai')->insertGetId([
            'id_level' => 1,
            'nama_pegawai' => 'John Doe',
            'no_pegawai' => 100,
            'jabatan' => 'HRD',
            'alamat' => 'Jakarta',
            'nohp' => '081234567890',
            'password' => Hash::make('password'),
            'boss' => null, // HRD tidak punya boss
            'foto' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ], 'id_pegawai'); // Menentukan kolom primary key untuk PostgreSQL

        // Tambahkan jatah untuk HRD
        DB::table('jatah_pegawai')->insert([
            'id_pegawai' => $hrdId,
            'jatah_wfa' => 12,
            'jatah_cuti' => 12,
            'sisa_wfa' => 12,
            'sisa_cuti' => 12,
            'tahun' => now()->year,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert Supervisors
        $supervisors = [];
        for ($i = 0; $i < 4; $i++) {
            $supervisorId = DB::table('pegawai')->insertGetId([
                'id_level' => 2,
                'nama_pegawai' => $faker->name,
                'no_pegawai' => $faker->unique()->numberBetween(101, 199),
                'jabatan' => 'Supervisor',
                'alamat' => $faker->city,
                'nohp' => $faker->phoneNumber,
                'password' => Hash::make('password'),
                'boss' => $hrdId, // Supervisor melapor ke HRD
                'foto' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ], 'id_pegawai'); // Menentukan kolom primary key

            // Tambahkan jatah untuk Supervisor
            DB::table('jatah_pegawai')->insert([
                'id_pegawai' => $supervisorId,
                'jatah_wfa' => 12,
                'jatah_cuti' => 12,
                'sisa_wfa' => 12,
                'sisa_cuti' => 12,
                'tahun' => now()->year,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $supervisors[] = $supervisorId;
        }

        // Insert Staff
        for ($i = 0; $i < 16; $i++) {
            $staffId = DB::table('pegawai')->insertGetId([
                'id_level' => 3,
                'nama_pegawai' => $faker->name,
                'no_pegawai' => $faker->unique()->numberBetween(200, 999),
                'jabatan' => 'Staff',
                'alamat' => $faker->city,
                'nohp' => $faker->phoneNumber,
                'password' => Hash::make('password'),
                'boss' => $faker->randomElement($supervisors), // Staff melapor ke salah satu Supervisor
                'foto' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ], 'id_pegawai'); // Menentukan kolom primary key

            // Tambahkan jatah untuk Staff
            DB::table('jatah_pegawai')->insert([
                'id_pegawai' => $staffId,
                'jatah_wfa' => 12,
                'jatah_cuti' => 12,
                'sisa_wfa' => 12,
                'sisa_cuti' => 12,
                'tahun' => now()->year,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
