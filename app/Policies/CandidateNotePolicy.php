<?php

namespace App\Policies;

use App\Models\CandidateNote;
use App\Models\User;

class CandidateNotePolicy
{
    /**
     * Determine whether the user can delete the note.
     */
    public function delete(User $user, CandidateNote $note): bool
    {
        return $note->user_id === $user->id || app('App\\Support\\CustomPermissionChecker')->check('edit_candidates', tenant());
    }
}
