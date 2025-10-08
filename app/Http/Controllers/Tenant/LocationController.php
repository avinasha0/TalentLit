<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index(string $tenant)
    {
        $query = Location::query()->orderBy('name');

        if (request('q')) {
            $search = trim(request('q'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $locations = $query->paginate(10)->withQueryString();

        return view('tenant.locations.index', compact('locations'));
    }

    public function create(string $tenant)
    {
        return view('tenant.locations.create');
    }

    public function store(Request $request, string $tenant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
        ]);

        Location::create([
            'tenant_id' => tenant_id(),
            'name' => $validated['name'],
            'code' => $validated['code'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return redirect()->route('tenant.locations.index', $tenant)
            ->with('success', 'Location created successfully.');
    }
}
