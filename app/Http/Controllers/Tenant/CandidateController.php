<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    public function index(Request $request, string $tenant)
    {
        $query = Candidate::with(['tags', 'applications.jobOpening']);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', '%'.$search.'%')
                    ->orWhere('last_name', 'like', '%'.$search.'%')
                    ->orWhere('primary_email', 'like', '%'.$search.'%');
            });
        }

        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        if ($request->filled('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->tag.'%');
            });
        }

        $candidates = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get filter options
        $sources = Candidate::pluck('source')
            ->filter()
            ->unique()
            ->sort()
            ->values();

        $tags = \App\Models\Tag::pluck('name')
            ->unique()
            ->sort()
            ->values();

        if ($request->expectsJson()) {
            return response()->json([
                'candidates' => $candidates->map(function ($candidate) {
                    return [
                        'id' => $candidate->id,
                        'name' => $candidate->full_name,
                        'email' => $candidate->primary_email,
                        'phone' => $candidate->primary_phone,
                        'source' => $candidate->source,
                        'tags' => $candidate->tags->map(function ($tag) {
                            return [
                                'name' => $tag->name,
                                'color' => $tag->color,
                            ];
                        }),
                        'applications_count' => $candidate->applications->count(),
                        'created_at' => $candidate->created_at,
                    ];
                }),
                'pagination' => [
                    'current_page' => $candidates->currentPage(),
                    'last_page' => $candidates->lastPage(),
                    'per_page' => $candidates->perPage(),
                    'total' => $candidates->total(),
                ],
            ]);
        }

        $tenant = tenant();
        return view('tenant.candidates.index', compact('candidates', 'sources', 'tags', 'tenant'));
    }

    public function show(string $tenant, Candidate $candidate)
    {
        $candidate->load([
            'tags',
            'resumes',
            'applications.jobOpening.department',
            'applications.currentStage',
            'applications.stageEvents',
        ]);

        $tenant = tenant();
        return view('tenant.candidates.show', compact('candidate', 'tenant'));
    }
}
