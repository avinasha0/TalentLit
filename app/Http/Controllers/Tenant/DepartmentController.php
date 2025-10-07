<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(string $tenant)
    {
        $query = Department::query()->orderBy('name');

        if (request('q')) {
            $search = trim(request('q'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $departments = $query->paginate(10)->withQueryString();

        return view('tenant.departments.index', compact('departments'));
    }

    public function create(string $tenant)
    {
        return view('tenant.departments.create');
    }

    public function store(Request $request, string $tenant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
        ]);

        Department::create([
            'tenant_id' => tenant_id(),
            'name' => $validated['name'],
            'code' => $validated['code'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return redirect()->route('tenant.departments.index', $tenant)
            ->with('success', 'Department created successfully.');
    }
}


