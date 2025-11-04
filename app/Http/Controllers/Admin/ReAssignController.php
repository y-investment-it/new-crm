<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadHistory;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReAssignController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'leads' => ['required', 'array'],
            'leads.*' => ['integer', 'exists:leads,id'],
            'user_id' => ['required', 'exists:users,id'],
            'type' => ['nullable', 'in:fresh,cold_call'],
            'duplicate' => ['boolean'],
            'same_stage' => ['boolean'],
            'salesman' => ['boolean'],
            'clear_history' => ['boolean'],
        ]);

        $target = User::findOrFail($data['user_id']);
        $now = now();

        DB::transaction(function () use ($data, $target, $now) {
            foreach ($data['leads'] as $leadId) {
                $lead = Lead::findOrFail($leadId);
                $payload = [
                    'from' => $lead->owner_id,
                    'to' => $target->id,
                    'duplicate' => (bool) ($data['duplicate'] ?? false),
                    'salesman' => (bool) ($data['salesman'] ?? false),
                ];

                if (! empty($data['duplicate'])) {
                    $newLead = $lead->replicate();
                    $newLead->stage = $data['type'] === 'cold_call' ? 'cold' : 'new';
                    $newLead->owner_id = $target->id;
                    $newLead->stage_date = $now;
                    $newLead->created_by = $target->id;
                    $newLead->save();

                    LeadHistory::create([
                        'lead_id' => $newLead->id,
                        'user_id' => $target->id,
                        'action' => 'duplicated',
                        'payload' => $payload,
                        'created_at' => $now,
                    ]);
                    continue;
                }

                $lead->owner_id = $target->id;

                if (! ($data['same_stage'] ?? false) && ! empty($data['type'])) {
                    $lead->stage = $data['type'] === 'cold_call' ? 'cold' : 'new';
                    $lead->stage_date = $now;
                }

                if (! empty($data['clear_history'])) {
                    $lead->comments()->delete();
                    $lead->histories()->delete();
                }

                $lead->save();

                LeadHistory::create([
                    'lead_id' => $lead->id,
                    'user_id' => $target->id,
                    'action' => 'assigned',
                    'payload' => $payload,
                    'created_at' => $now,
                ]);

                if (! ($data['same_stage'] ?? false) && ! empty($data['type'])) {
                    LeadHistory::create([
                        'lead_id' => $lead->id,
                        'user_id' => $target->id,
                        'action' => 'stage_changed',
                        'payload' => ['stage' => $lead->stage],
                        'created_at' => $now,
                    ]);
                }
            }
        });

        return response()->json(['status' => 'ok']);
    }
}
