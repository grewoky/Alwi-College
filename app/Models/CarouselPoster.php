<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarouselPoster extends Model
{
    protected $fillable = [
        'cloudinary_public_id',
        'cloudinary_secure_url',
        'cloudinary_format',
        'cloudinary_original_filename',
        'position',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'position' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('position')->orderBy('id');
    }

    public function transformedUrl(string $transformation): string
    {
        $url = (string) ($this->cloudinary_secure_url ?? '');
        if ($url === '') {
            return '';
        }

        // Cloudinary URL format typically contains "/upload/" segment.
        if (str_contains($url, '/upload/')) {
            return str_replace('/upload/', '/upload/' . trim($transformation, '/') . '/', $url);
        }

        return $url;
    }
}
