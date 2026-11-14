<?php

namespace App\Http\Controllers\Moment;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;

class PageController extends Controller
{
    public function home(): View
    {
        return view('moment.home', $this->content());
    }

    public function package(string $package): View
    {
        $content = $this->content();
        abort_unless(isset($content['packages'][$package]), 404);

        return view('moment.package', [
            ...$content,
            'package' => $content['packages'][$package],
            'packageSlug' => $package,
        ]);
    }

    public function presets(): View
    {
        return view('moment.presets', $this->content());
    }

    public function notFound(): Response
    {
        return response()->view('moment.not-found', $this->content(), 404);
    }

    private function content(): array
    {
        $content = config('moment');
        $content['whatsappUrl'] = 'https://wa.me/'.$content['whatsapp'];

        return $content;
    }
}
