<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(): View
    {
        $allLeads = Lead::count();
        $freshLeads = Lead::where('stage', 'new')->count();
        $coldLeads = Lead::where('stage', 'cold')->count();
        $duplicateLeads = 0; // placeholder for duplicates detection

        $stageCounts = Lead::select('stage', DB::raw('count(*) as total'))
            ->groupBy('stage')
            ->pluck('total', 'stage');

        $delays = collect([
            'no_answer', 'first_call', 'presentation', 'follow_up', 'negotiation', 'hot', 'cold',
        ])->mapWithKeys(fn ($stage) => [
            $stage => Lead::where('stage', $stage)->count(),
        ]);

        $recentLeads = Lead::with('owner')
            ->latest('updated_at')
            ->take(10)
            ->get();

        $topUsers = User::withCount([
            'leads as won_leads_count' => fn ($query) => $query->where('stage', 'won'),
            'leads as active_leads_count' => fn ($query) => $query->whereIn('stage', ['hot', 'negotiation', 'follow_up']),
        ])->orderByDesc('won_leads_count')->take(5)->get();

        return view('admin.dashboard', [
            'stats' => [
                'all' => $allLeads,
                'duplicates' => $duplicateLeads,
                'fresh' => $freshLeads,
                'cold' => $coldLeads,
            ],
            'stageCounts' => $stageCounts,
            'delays' => $delays,
            'recentLeads' => $recentLeads,
            'topUsers' => $topUsers,
        ]);
    }
}
