<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|min:2|max:150',
            'author' => 'required|string|min:3|max:100',
            'description' => 'required|string|min:10',
            'year' => 'required|integer|min:1500|max:' . date('Y'),
            'available_copies' => 'required|integer|min:1',
        ];
    }
}
