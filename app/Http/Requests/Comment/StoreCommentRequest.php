<?php

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $user = $this->user();
        $guestRequired = $user === null;

        return [
            'content' => ['required', 'string', 'min:3'],
            'parent_id' => ['nullable', 'integer', 'exists:comments,id'],
            'guest_name' => [$guestRequired ? 'required' : 'nullable', 'string', 'max:120'],
            'guest_email' => [$guestRequired ? 'required' : 'nullable', 'string', 'email', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'content.required' => 'Konten komentar wajib diisi.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('guest_email')) {
            $this->merge([
                'guest_email' => strtolower((string) $this->input('guest_email')),
            ]);
        }
    }
}
