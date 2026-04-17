<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display My Tasks page
     * GET /tasks/my
     */
    public function index(Request $request, string $tenant = null)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                abort(401, 'Unauthorized');
            }

            return view('tenant.tasks.my');
        } catch (\Exception $e) {
            \Log::error('Failed to load tasks page', [
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'Failed to load tasks page.');
        }
    }
}

