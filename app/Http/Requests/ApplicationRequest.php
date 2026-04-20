<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'application_type' => 'required|string|in:motor,mobil,multiguna',
            'nominal' => 'required|numeric|max:200000000',
            'tenor' => 'required|integer',
            'income' => 'required|numeric|min:1000000',
        ];
    }


    public function messages()
    {
        return [
            'name.required' => 'Nama harus diisi.',
            'application_type.required' => 'Tipe aplikasi harus diisi.',
            'application_type.in' => 'Tipe aplikasi harus berupa motor, mobil, atau multiguna.',
            'nominal.required' => 'Nominal harus diisi.',
            'nominal.numeric' => 'Nominal harus berupa angka.',
            'nominal.max' => 'Nominal tidak boleh lebih dari 200 juta.',
            'tenor.required' => 'Tenor harus diisi.',
            'tenor.integer' => 'Tenor harus berupa angka bulat.',
            'income.required' => 'Pendapatan harus diisi.',
            'income.numeric' => 'Pendapatan harus berupa angka.',
            'income.min' => 'Pendapatan tidak boleh kurang dari 1 juta.',
        ];
    }
}
