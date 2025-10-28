<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Post;
use Illuminate\Validation\Rule;

class UpdatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        if (! $user) {
            return false;
        }

        if ($user->can('manage-posts')) {
            return true;
        }

        $post = $this->route('post');

        if (! $post instanceof Post) {
            return false;
        }

        return $user->id === $post->author_id;
    }

    public function rules(): array
    {
        $post = $this->route('post');
        $postId = $post instanceof Post ? $post->id : null;

        return [
            'title' => ['sometimes', 'required', 'string', 'max:200'],
            'slug' => [
                'nullable',
                'string',
                'max:200',
                Rule::unique('posts', 'slug')->ignore($postId),
            ],
            'excerpt' => ['nullable', 'string'],
            'content' => ['sometimes', 'required', 'string'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['integer', 'exists:tags,id'],
            'status' => ['nullable', Rule::in(['draft', 'published', 'scheduled', 'archived'])],
            'type' => ['nullable', Rule::in(['article', 'page', 'news'])],
            'published_at' => ['nullable', 'date'],
            'scheduled_at' => ['nullable', 'date'],
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
            'slug.max' => 'Slug maksimal :max karakter.',
            'slug.unique' => 'Slug sudah digunakan.',
            'content.required' => 'Konten wajib diisi.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'tags.*.exists' => 'Tag yang dipilih tidak valid.',
            'status.in' => 'Status yang dipilih tidak valid.',
            'type.in' => 'Tipe yang dipilih tidak valid.',
            'seo_title.max' => 'Judul SEO maksimal :max karakter.',
            'seo_description.max' => 'Deskripsi SEO maksimal :max karakter.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('status') && blank($this->input('status'))) {
            $this->merge(['status' => 'draft']);
        }

        if ($this->has('type') && blank($this->input('type'))) {
            $this->merge(['type' => 'article']);
        }
    }
}
