<?php

namespace App\Http\Requests\Api\v1;

use Illuminate\Foundation\Http\FormRequest;

class StoreVideoPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'video' => ['required', 'file', 'mimes:mp4,avi,mov,wmv,flv,webm', 'max:102400'], // 100MB max
        ];
    }
}
