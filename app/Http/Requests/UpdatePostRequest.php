<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|min:3',
            'excerpt' => 'required|min:10',
            'body' => 'required|min:10',
            'is_published' => 'nullable',
            'published_at' => 'nullable',
            'user_id' => 'nullable',
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'remove_image' => ['sometimes', 'boolean']
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Пожалуйта, введите заголовок',
            'excerpt.required' => 'Пожалуйта, введите короткое превью',
            'body.required' => 'Пожалуйта, введите текст поста',
            'image.image' => 'Файл должен быть изображением',
            'image.max' => 'Файл слишком большой',
            'image.mimes' => 'Поддерживаются только форматы jpeg, png, jpg, webp'
        ];
    }
}