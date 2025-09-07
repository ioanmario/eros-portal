<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyMtRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'server'   => ['required','string','max:120'],
            'login'    => ['required','string','max:64'],
            'password' => ['required','string','max:128'],
            'start_date' => ['nullable','date'],
        ];
    }
}
