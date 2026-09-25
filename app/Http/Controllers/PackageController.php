<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\View\View;

class PackageController extends Controller
{
    public function index(): View
    {
        $packages = Package::where('is_active', true)->with('facilities')->latest()->paginate(9);

        return view('packages.index', compact('packages'));
    }

    public function show(Package $package): View
    {
        abort_if(! $package->is_active, 404);

        $package->load('facilities');

        return view('packages.show', compact('package'));
    }
}
