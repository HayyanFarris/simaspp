<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Admin bisa tambah semua, guru hanya bisa tambah ke kelasnya (cek di controller)
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'nis' => ['required', 'string', 'max:20', 'unique:siswa,nis'],
            'nama' => ['required', 'string', 'max:255'],
            'kelas_id' => ['required', 'exists:kelas,id'],
            'nominal_spp' => ['required', 'numeric', 'min:0'],
            'nama_orang_tua' => ['nullable', 'string', 'max:255'],
            'no_telepon_orang_tua' => ['nullable', 'string', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'nis.required' => 'NIS wajib diisi.',
            'nis.unique' => 'NIS sudah digunakan.',
            'nama.required' => 'Nama siswa wajib diisi.',
            'kelas_id.required' => 'Kelas wajib dipilih.',
            'kelas_id.exists' => 'Kelas yang dipilih tidak valid.',
            'nominal_spp.required' => 'Nominal SPP wajib diisi.',
            'nominal_spp.numeric' => 'Nominal SPP harus berupa angka.',
            'nominal_spp.min' => 'Nominal SPP tidak boleh negatif.',
        ];
    }
}
