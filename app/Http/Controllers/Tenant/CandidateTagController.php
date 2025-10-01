<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CandidateTagController extends Controller
{
    public function index(Request $request)
    {
        $tags = Tag::when($request->q, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })->limit(10)->get();

        return response()->json($tags->map(function ($tag) {
            return [
                'id' => $tag->id,
                'name' => $tag->name,
            ];
        }));
    }

    public function store(Request $request, string $tenant, Candidate $candidate)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Ensure candidate belongs to current tenant
        if ($candidate->tenant_id !== tenant_id()) {
            abort(404);
        }

        // Check authorization
        if (!Auth::user()->hasAnyRole(['Owner', 'Admin', 'Recruiter'])) {
            abort(403, 'You do not have permission to manage tags.');
        }

        // Find or create tag
        $tag = Tag::firstOrCreate(
            [
                'tenant_id' => tenant_id(),
                'name' => $request->name,
            ]
        );

        // Attach tag to candidate if not already attached
        if (!$candidate->tags()->where('candidate_tags.id', $tag->id)->exists()) {
            $candidate->tags()->attach($tag->id, ['tenant_id' => tenant_id()]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'tag' => [
                    'id' => $tag->id,
                    'name' => $tag->name,
                ]
            ]);
        }

        return redirect()->back()->with('success', 'Tag added successfully.');
    }

    public function destroy(Request $request, string $tenant, Candidate $candidate, Tag $tag)
    {
        // Ensure candidate and tag belong to current tenant
        if ($candidate->tenant_id !== tenant_id() || $tag->tenant_id !== tenant_id()) {
            abort(404);
        }

        // Check authorization
        if (!Auth::user()->hasAnyRole(['Owner', 'Admin', 'Recruiter'])) {
            abort(403, 'You do not have permission to manage tags.');
        }

        // Detach tag from candidate
        $candidate->tags()->detach($tag->id);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Tag removed successfully.');
    }
}
