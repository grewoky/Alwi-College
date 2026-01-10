<?php

namespace App\Http\Controllers;

use App\Models\CarouselPoster;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CarouselPosterController extends Controller
{
    private function resequencePositions(): void
    {
        $posters = CarouselPoster::query()->ordered()->get(['id']);
        foreach ($posters as $idx => $poster) {
            CarouselPoster::whereKey($poster->id)->update(['position' => $idx + 1]);
        }
    }

    public function index()
    {
        $posters = CarouselPoster::query()->ordered()->get();

        return view('admin.carousel-posters.index', compact('posters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cloudinary_public_id' => 'required|string|max:255',
            'cloudinary_secure_url' => 'required|url|max:2048',
            'cloudinary_format' => 'nullable|string|max:20',
            'cloudinary_original_filename' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request) {
            $nextPosition = (int) (CarouselPoster::max('position') ?? 0) + 1;

            CarouselPoster::create([
                'cloudinary_public_id' => $request->cloudinary_public_id,
                'cloudinary_secure_url' => $request->cloudinary_secure_url,
                'cloudinary_format' => $request->cloudinary_format,
                'cloudinary_original_filename' => $request->cloudinary_original_filename,
                'position' => $nextPosition,
                'is_active' => true,
            ]);

            $this->resequencePositions();
        });

        return back()->with('ok', 'Poster carousel berhasil ditambahkan.');
    }

    public function moveUp(CarouselPoster $poster)
    {
        DB::transaction(function () use ($poster) {
            $orderedIds = CarouselPoster::query()->ordered()->pluck('id')->all();
            $idx = array_search($poster->id, $orderedIds, true);
            if ($idx === false || $idx === 0) {
                return;
            }

            $prevId = $orderedIds[$idx - 1];
            $prev = CarouselPoster::find($prevId);
            if (! $prev) {
                return;
            }

            $currentPos = $poster->position;
            $poster->position = $prev->position;
            $prev->position = $currentPos;
            $poster->save();
            $prev->save();

            $this->resequencePositions();
        });

        return back()->with('ok', 'Urutan poster diperbarui.');
    }

    public function moveDown(CarouselPoster $poster)
    {
        DB::transaction(function () use ($poster) {
            $orderedIds = CarouselPoster::query()->ordered()->pluck('id')->all();
            $idx = array_search($poster->id, $orderedIds, true);
            if ($idx === false || $idx >= count($orderedIds) - 1) {
                return;
            }

            $nextId = $orderedIds[$idx + 1];
            $next = CarouselPoster::find($nextId);
            if (! $next) {
                return;
            }

            $currentPos = $poster->position;
            $poster->position = $next->position;
            $next->position = $currentPos;
            $poster->save();
            $next->save();

            $this->resequencePositions();
        });

        return back()->with('ok', 'Urutan poster diperbarui.');
    }

    public function destroy(CarouselPoster $poster)
    {
        try {
            if ($poster->cloudinary_public_id) {
                try {
                    Cloudinary::destroy($poster->cloudinary_public_id, ['invalidate' => true, 'resource_type' => 'image']);
                } catch (\Throwable $th) {
                    Log::warning('Cloudinary destroy carousel poster failed', [
                        'poster_id' => $poster->id,
                        'public_id' => $poster->cloudinary_public_id,
                        'error' => $th->getMessage(),
                    ]);
                }
            }

            DB::transaction(function () use ($poster) {
                $poster->delete();
                $this->resequencePositions();
            });

            return back()->with('ok', 'Poster carousel berhasil dihapus.');
        } catch (\Throwable $th) {
            Log::error('Delete carousel poster failed', [
                'poster_id' => $poster->id,
                'error' => $th->getMessage(),
            ]);

            return back()->with('error', 'Gagal menghapus poster: ' . $th->getMessage());
        }
    }
}
