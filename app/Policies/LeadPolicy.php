<?php

namespace App\Policies;

use App\Models\Lead;
use App\Models\User;

class LeadPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'manager', 'agent']);
    }

    public function view(User $user, Lead $lead): bool
    {
        if ($user->hasRole('admin') || $user->hasRole('manager')) {
            return true;
        }

        return $lead->owner_id === $user->id;
    }

    public function update(User $user, Lead $lead): bool
    {
        if ($user->hasRole('admin') || $user->hasRole('manager')) {
            return true;
        }

        return $lead->owner_id === $user->id;
    }

    public function reassign(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'manager']);
    }
}
