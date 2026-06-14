<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::latest()->paginate(10);
        $stats = [
            'total'      => Package::count(),
            'active'     => Package::where('status', 'Active')->count(),
            'avg_price'  => Package::where('status', 'Active')->avg('price') ?? 0,
            'categories' => Package::distinct('category')->count('category'),
        ];
        return view('packages.index', compact('packages', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'required|in:Photography,Videography,Combo',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'status'      => 'required|in:Active,Draft,Archived',
            'features'    => 'nullable|string',
        ]);

        $features = null;
        if (!empty($validated['features'])) {
            $features = array_filter(
                array_map('trim', explode("\n", $validated['features']))
            );
        }

        Package::create([
            'name'        => $validated['name'],
            'category'    => $validated['category'],
            'description' => $validated['description'],
            'price'       => $validated['price'],
            'status'      => $validated['status'],
            'features'    => $features ? array_values($features) : null,
        ]);

        return redirect()->route('packages.index')
            ->with('success', 'Package created successfully!');
    }

    public function update(Request $request, Package $package)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'required|in:Photography,Videography,Combo',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'status'      => 'required|in:Active,Draft,Archived',
            'features'    => 'nullable|string',
        ]);

        $features = null;
        if (!empty($validated['features'])) {
            $features = array_filter(
                array_map('trim', explode("\n", $validated['features']))
            );
        }

        $package->update([
            'name'        => $validated['name'],
            'category'    => $validated['category'],
            'description' => $validated['description'],
            'price'       => $validated['price'],
            'status'      => $validated['status'],
            'features'    => $features ? array_values($features) : null,
        ]);

        return redirect()->route('packages.index')
            ->with('success', 'Package updated successfully!');
    }

    public function destroy(Package $package)
    {
        $package->delete();
        return redirect()->route('packages.index')
            ->with('success', 'Package deleted successfully!');
    }
}