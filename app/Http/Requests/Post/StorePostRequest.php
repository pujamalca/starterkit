<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manage-posts') ?? false;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:200'],
            'slug' => ['required', 'string', 'max:200', 'unique:posts,slug'],
            'excerpt' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['integer', 'exists:tags,id'],
            'status' => ['required', Rule::in(['draft', 'published', 'scheduled', 'archived'])],
            'type' => ['required', Rule::in(['article', 'page', 'news'])],
            'published_at' => ['nullable', 'date'],
            'scheduled_at' => ['nullable', 'date', 'after:now'],
            'is_featured' => ['nullable', 'boolean'],
            'is_sticky' => ['nullable', 'boolean'],
            'featured_image' => ['nullable', 'string', 'max:255'],
            'gallery' => ['nullable', 'array'],
            'gallery.*' => ['string', 'max:255'],
            'seo_title' => ['nullable', 'string', 'max:60'],
            'seo_description' => ['nullable', 'string', 'max:160'],
            'seo_keywords' => ['nullable', 'string'],
            'og_image' => ['nullable', 'string', 'max:255'],
            'metadata' => ['nullable', 'array'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul wajib diisi.',
            'title.max' => 'Judul maksimal :max karakter.',
            'slug.required' => 'Slug wajib diisi.',
            'slug.max' => 'Slug maksimal :max karakter.',
            'slug.unique' => 'Slug sudah digunakan.',
            'content.required' => 'Konten wajib diisi.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'tags.*.exists' => 'Tag yang dipilih tidak valid.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status yang dipilih tidak valid.',
            'type.required' => 'Tipe wajib dipilih.',
            'type.in' => 'Tipe yang dipilih tidak valid.',
            'scheduled_at.after' => 'Jadwal publikasi harus setelah waktu sekarang.',
            'seo_title.max' => 'Judul SEO maksimal :max karakter.',
            'seo_description.max' => 'Deskripsi SEO maksimal :max karakter.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if (! $this->filled('status')) {
            $this->merge(['status' => 'draft']);
        }

        if (! $this->filled('type')) {
            $this->merge(['type' => 'article']);
        }
    }
}
