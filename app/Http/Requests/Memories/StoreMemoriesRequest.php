<?php

namespace App\Http\Requests\Memories;

use Illuminate\Foundation\Http\FormRequest;

class StoreMemoriesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'images' => ['required', 'array', 'min:1', 'max:20'],
            'images.*' => ['file', 'mimes:jpeg,jpg,png,webp,heic,heif', 'max:25600'],
        ];
    }
}
