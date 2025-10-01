<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\CandidateNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CandidateNoteController extends Controller
{
    public function store(Request $request, string $tenant, Candidate $candidate)
    {
        try {
            \Log::info('Create note request', [
                'tenant' => $tenant,
                'candidate_id' => $candidate->id,
                'user_id' => Auth::id(),
                'tenant_id' => tenant_id(),
                'body_length' => strlen($request->body ?? ''),
                'expects_json' => $request->expectsJson()
            ]);

            $request->validate([
                'body' => 'required|string|max:5000',
            ]);

            // Candidate is already scoped to tenant via route model binding

            $note = CandidateNote::create([
                'tenant_id' => tenant_id(),
                'candidate_id' => $candidate->id,
                'user_id' => Auth::id(),
                'body' => $request->body,
            ]);

            $note->load('user');

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'note' => [
                        'id' => $note->id,
                        'body' => $note->body,
                        'user_name' => $note->user->name,
                        'created_at' => $note->created_at->format('M j, Y g:i A'),
                    ]
                ]);
            }

            return redirect()->back()->with('success', 'Note added successfully.');
        } catch (\Exception $e) {
            \Log::error('Error creating note: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Error creating note: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Error creating note: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request, string $tenant, Candidate $candidate, CandidateNote $note)
    {
        try {
            \Log::info('Delete note request', [
                'tenant' => $tenant,
                'candidate_id' => $candidate->id,
                'note_id' => $note->id,
                'user_id' => Auth::id(),
                'tenant_id' => tenant_id(),
                'note_candidate_id' => $note->candidate_id,
                'note_tenant_id' => $note->tenant_id,
                'note_user_id' => $note->user_id
            ]);

            // Ensure note belongs to candidate and tenant
            if ($note->candidate_id !== $candidate->id || $note->tenant_id !== tenant_id()) {
                \Log::warning('Note does not belong to candidate or tenant', [
                    'note_candidate_id' => $note->candidate_id,
                    'candidate_id' => $candidate->id,
                    'note_tenant_id' => $note->tenant_id,
                    'current_tenant_id' => tenant_id()
                ]);
                abort(404);
            }

            // Check authorization
            if ($note->user_id !== Auth::id() && !Auth::user()->hasAnyRole(['Owner', 'Admin'])) {
                \Log::warning('Unauthorized note deletion attempt', [
                    'user_id' => Auth::id(),
                    'note_id' => $note->id,
                    'note_user_id' => $note->user_id
                ]);
                abort(403, 'You can only delete your own notes.');
            }

            $note->delete();
            \Log::info('Note deleted successfully', ['note_id' => $note->id]);

            if ($request->expectsJson()) {
                return response()->json(['success' => true]);
            }

            return redirect()->back()->with('success', 'Note deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting note: ' . $e->getMessage(), [
                'tenant' => $tenant,
                'candidate_id' => $candidate->id ?? 'unknown',
                'note_id' => $note->id ?? 'unknown',
                'user_id' => Auth::id(),
                'tenant_id' => tenant_id(),
                'exception' => $e->getTraceAsString()
            ]);
            
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Error deleting note'], 500);
            }
            
            return redirect()->back()->with('error', 'Error deleting note');
        }
    }
}
