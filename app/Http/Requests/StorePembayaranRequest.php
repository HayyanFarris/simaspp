<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePembayaranRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Hanya guru yang diizinkan mencatat pembayaran
        return auth()->user()->isGuru();
    }

    public function rules(): array
    {
        return [
            'siswa_id' => ['required', 'exists:siswa,id'],
            'bulan' => ['required', 'string', 'max:20'],
            'tahun_ajaran' => ['required', 'string', 'max:9'],
            'jumlah_dibayar' => ['required', 'numeric', 'min:0'],
            'tanggal_bayar' => ['required', 'date'],
            'status' => ['required', Rule::in(['lunas', 'belum_lunas', 'cicil'])],
            'keterangan' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'siswa_id.required' => 'Siswa wajib dipilih.',
            'siswa_id.exists' => 'Siswa yang dipilih tidak valid.',
            'bulan.required' => 'Bulan wajib diisi.',
            'tahun_ajaran.required' => 'Tahun ajaran wajib diisi.',
            'jumlah_dibayar.required' => 'Jumlah dibayar wajib diisi.',
            'jumlah_dibayar.numeric' => 'Jumlah dibayar harus berupa angka.',
            'jumlah_dibayar.min' => 'Jumlah dibayar tidak boleh negatif.',
            'tanggal_bayar.required' => 'Tanggal bayar wajib diisi.',
            'tanggal_bayar.date' => 'Tanggal bayar tidak valid.',
            'status.required' => 'Status pembayaran wajib dipilih.',
            'status.in' => 'Status pembayaran tidak valid.',
        ];
    }
}
