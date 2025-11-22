<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Tag;
use App\Models\CandidateTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        // Check if tag is already attached to candidate
        $exists = DB::table('candidate_tags')
            ->where('tenant_id', tenant_id())
            ->where('candidate_id', $candidate->id)
            ->where('tag_id', $tag->id)
            ->exists();

        // Attach tag to candidate if not already attached
        if (!$exists) {
            // Use the pivot model directly to ensure UUID is generated
            CandidateTag::create([
                'tenant_id' => tenant_id(),
                'candidate_id' => $candidate->id,
                'tag_id' => $tag->id,
            ]);
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
