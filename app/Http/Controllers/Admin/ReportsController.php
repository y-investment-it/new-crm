<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function index(): View
    {
        $stageSummary = Lead::select('stage', DB::raw('count(*) as total'))
            ->groupBy('stage')
            ->orderBy('stage')
            ->get();

        $createdPerDay = Lead::select(DB::raw('DATE(created_at) as day'), DB::raw('count(*) as total'))
            ->where('created_at', '>=', now()->subDays(14))
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $topAgents = User::role('agent')
            ->withCount([
                'leads as won_leads_count' => fn ($query) => $query->where('stage', 'won'),
                'leads as activity_count' => fn ($query) => $query->join('lead_histories', 'leads.id', '=', 'lead_histories.lead_id')
                    ->whereColumn('lead_histories.user_id', 'leads.owner_id'),
            ])->orderByDesc('won_leads_count')->take(5)->get();

        return view('admin.reports.index', [
            'stageSummary' => $stageSummary,
            'createdPerDay' => $createdPerDay,
            'topAgents' => $topAgents,
        ]);
    }
}
