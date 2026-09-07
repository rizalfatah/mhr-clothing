<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommunityImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CommunityImageController extends Controller
{
    public function index(): View
    {
        $communityImages = CommunityImage::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('admin.community-images.index', compact('communityImages'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules(true), $this->messages());
        $path = null;

        try {
            [$width, $height] = $this->getImageDimensions($request->file('image'));
            $path = $request->file('image')->store('community', 'public');

            if (! $path) {
                throw new \RuntimeException('Gagal menyimpan file gambar.');
            }

            CommunityImage::create([
                'image_path' => $path,
                'alt_text' => $validated['alt_text'],
                'caption' => $validated['caption'] ?? null,
                'width' => $width,
                'height' => $height,
                'sort_order' => $validated['sort_order'] ?? (CommunityImage::max('sort_order') ?? -1) + 1,
                'is_active' => $request->boolean('is_active'),
            ]);

            return redirect()
                ->route('admin.community-images.index')
                ->with('success', 'Gambar komunitas berhasil ditambahkan.');
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            if ($path) {
                Storage::disk('public')->delete($path);
            }

            return back()
                ->withInput()
                ->with('error', 'Gagal menambahkan gambar komunitas: '.$e->getMessage());
        }
    }

    public function update(Request $request, CommunityImage $communityImage): RedirectResponse
    {
        $validated = $request->validate($this->rules(false), $this->messages());
        $newPath = null;

        try {
            $attributes = [
                'alt_text' => $validated['alt_text'],
                'caption' => $validated['caption'] ?? null,
                'sort_order' => $validated['sort_order'],
                'is_active' => $request->boolean('is_active'),
            ];

            if ($request->hasFile('image')) {
                [$width, $height] = $this->getImageDimensions($request->file('image'));
                $newPath = $request->file('image')->store('community', 'public');

                if (! $newPath) {
                    throw new \RuntimeException('Gagal menyimpan file gambar.');
                }

                $attributes = array_merge($attributes, [
                    'image_path' => $newPath,
                    'width' => $width,
                    'height' => $height,
                ]);
            }

            $oldPath = $communityImage->image_path;
            $oldImageIsManaged = $communityImage->isManagedUpload();

            $communityImage->update($attributes);

            if ($newPath && $oldImageIsManaged) {
                Storage::disk('public')->delete($oldPath);
            }

            return redirect()
                ->route('admin.community-images.index')
                ->with('success', 'Gambar komunitas berhasil diperbarui.');
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            if ($newPath) {
                Storage::disk('public')->delete($newPath);
            }

            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui gambar komunitas: '.$e->getMessage());
        }
    }

    public function destroy(CommunityImage $communityImage): RedirectResponse
    {
        try {
            $path = $communityImage->image_path;
            $isManagedUpload = $communityImage->isManagedUpload();

            $communityImage->delete();

            if ($isManagedUpload) {
                Storage::disk('public')->delete($path);
            }

            return redirect()
                ->route('admin.community-images.index')
                ->with('success', 'Gambar komunitas berhasil dihapus.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal menghapus gambar komunitas: '.$e->getMessage());
        }
    }

    private function rules(bool $imageRequired): array
    {
        return [
            'image' => [$imageRequired ? 'required' : 'nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
            'alt_text' => ['required', 'string', 'max:255'],
            'caption' => ['nullable', 'string', 'max:255'],
            'sort_order' => [$imageRequired ? 'nullable' : 'required', 'integer', 'min:0'],
            'is_active' => ['required', 'boolean'],
        ];
    }

    private function messages(): array
    {
        return [
            'image.required' => 'Pilih gambar yang akan ditampilkan.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus JPG, JPEG, PNG, atau WEBP.',
            'image.max' => 'Ukuran gambar maksimal 5MB.',
            'alt_text.required' => 'Teks alternatif wajib diisi.',
        ];
    }

    private function getImageDimensions(UploadedFile $image): array
    {
        $dimensions = getimagesize($image->getRealPath());

        if ($dimensions === false) {
            throw ValidationException::withMessages([
                'image' => 'Dimensi gambar tidak dapat dibaca.',
            ]);
        }

        return [$dimensions[0], $dimensions[1]];
    }
}
