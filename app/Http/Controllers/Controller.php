<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

abstract class Controller
{
    /**
     * Enforce the 2MB per-image limit that Vercel requires for uploads.
     */
    protected function enforceImageSizeLimit(Request $request, array $fields, ?string $message = null): void
    {
        $limitInBytes = 2048 * 1024;
        foreach ($fields as $field) {
            $candidates = Arr::flatten(Arr::wrap($request->file($field)));

            foreach ($candidates as $file) {
                if (! $file instanceof UploadedFile || ! $file->isValid()) {
                    continue;
                }

                $mime = $file->getMimeType() ?? '';
                if (str_starts_with($mime, 'image/') && $file->getSize() > $limitInBytes) {
                    throw ValidationException::withMessages([
                        $field => $message ?? 'Setiap gambar maksimal 2MB. Silakan kompres file sebelum mengunggah.',
                    ]);
                }
            }
        }
    }
}
