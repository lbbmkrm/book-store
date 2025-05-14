<?php

namespace App\Http\Requests\Book;

use Illuminate\Foundation\Http\FormRequest;

class CreateBookRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'category_id' => 'required|integer',
            'title' => 'required|string|unique:books,title|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'stock' => 'sometimes|integer',
            'price' => 'required|integer',
            'img' => 'nullable|image|max:2048'
        ];
    }
}
