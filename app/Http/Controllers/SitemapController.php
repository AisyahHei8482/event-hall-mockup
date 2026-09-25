<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Package;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $urls = collect([
            ['loc' => route('home'), 'priority' => '1.0'],
            ['loc' => route('facilities.index'), 'priority' => '0.9'],
            ['loc' => route('packages.index'), 'priority' => '0.9'],
            ['loc' => route('contact.create'), 'priority' => '0.5'],
        ]);

        Facility::where('is_active', true)->get()->each(function (Facility $facility) use ($urls) {
            $urls->push([
                'loc' => route('facilities.show', $facility),
                'priority' => '0.8',
                'lastmod' => $facility->updated_at->toAtomString(),
            ]);
        });

        Package::where('is_active', true)->get()->each(function (Package $package) use ($urls) {
            $urls->push([
                'loc' => route('packages.show', $package),
                'priority' => '0.7',
                'lastmod' => $package->updated_at->toAtomString(),
            ]);
        });

        $xml = view('sitemap', compact('urls'))->render();

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}
