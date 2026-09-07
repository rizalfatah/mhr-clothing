<?php

namespace App\Http\Controllers;

use App\Models\CommunityImage;
use Illuminate\View\View;

class CommunityController extends Controller
{
    public function index(): View
    {
        $communityImages = CommunityImage::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(fn (CommunityImage $image) => [
                'src' => $image->image_url,
                'alt' => $image->alt_text,
                'caption' => $image->caption,
                'width' => $image->width,
                'height' => $image->height,
            ])
            ->all();

        return view('community', compact('communityImages'));
    }
}
