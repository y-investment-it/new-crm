<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index(Request $request): View
    {
        $query = Lead::with('owner');

        if ($search = $request->input('q')) {
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($stage = $request->input('stage')) {
            $query->where('stage', $stage);
        }

        if ($ownerId = $request->input('owner_id')) {
            $query->where('owner_id', $ownerId);
        }

        $leads = $query->orderByDesc('updated_at')->paginate(25)->withQueryString();
        $owners = User::orderBy('name')->get(['id', 'name']);

        return view('admin.leads.index', [
            'leads' => $leads,
            'owners' => $owners,
            'filters' => $request->only(['q', 'stage', 'owner_id']),
        ]);
    }

    public function show(Lead $lead): View
    {
        $lead->load(['owner', 'creator', 'comments.user', 'histories.user']);

        return view('admin.leads.show', [
            'lead' => $lead,
        ]);
    }
}
