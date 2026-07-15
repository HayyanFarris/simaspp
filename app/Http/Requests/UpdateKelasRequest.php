<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateKelasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'nama_kelas' => [
                'required',
                'string',
                'max:50',
                Rule::unique('kelas', 'nama_kelas')->ignore($this->kelas),
            ],
            'tingkat' => ['required', 'string', 'max:10'],
            'wali_kelas_id' => [
                'required',
                'exists:users,id',
                Rule::exists('users', 'id')->where('role', 'guru'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_kelas.required' => 'Nama kelas wajib diisi.',
            'nama_kelas.unique' => 'Nama kelas sudah digunakan.',
            'tingkat.required' => 'Tingkat kelas wajib diisi.',
            'wali_kelas_id.required' => 'Wali kelas wajib dipilih.',
            'wali_kelas_id.exists' => 'Guru yang dipilih tidak valid.',
        ];
    }
}
