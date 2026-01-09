<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class GenerateLessonRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'school'     => 'required|in:Negeri,IGS,Xavega,Bangau,Kumbang',
            'grade'      => 'required|in:10,11,12',
            'teacher_id' => 'required|exists:teachers,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'per_variant' => 'nullable',
            'description' => 'nullable|string|max:500',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time'   => 'nullable|date_format:H:i',
        ];
    }

    /**
     * Custom messages untuk validation errors
     */
    public function messages(): array
    {
        return [
            'school.required' => 'Sekolah harus dipilih',
            'grade.required' => 'Kelas harus dipilih',
            'teacher_id.required' => 'Guru harus dipilih',
            'start_date.required' => 'Tanggal mulai harus diisi',
            'end_date.required' => 'Tanggal selesai harus diisi',
            'end_date.after_or_equal' => 'Tanggal selesai harus >= tanggal mulai',
            'start_time.date_format' => 'Format jam mulai harus HH:mm',
            'end_time.date_format' => 'Format jam selesai harus HH:mm',
        ];
    }

    /**
     * Handle failed validation
     * Jika ada error, hanya reset field time jika error di time, field lain tetap terisi
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        $inputToKeep = $this->all();

        // Jika error hanya di time fields, hapus input time saja
        if ($errors->has('start_time') || $errors->has('end_time')) {
            unset($inputToKeep['start_time'], $inputToKeep['end_time']);
        }

        throw new HttpResponseException(
            back()
                ->withErrors($errors)
                ->withInput($inputToKeep)
        );
    }
}
