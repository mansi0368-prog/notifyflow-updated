<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CampaignController extends Controller
{
    public function index(): Response
    {
        $campaigns = Campaign::forUser(auth()->id())
            ->latest()
            ->paginate(10);

        return Inertia::render('Campaigns/Index', [
            'campaigns' => $campaigns,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Campaigns/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'subject'      => 'required|string|max:255',
            'body'         => 'required|string',
            'scheduled_at' => 'nullable|date|after:now',
        ]);

        Campaign::create([
            ...$validated,
            'user_id' => auth()->id(),
            'status'  => $validated['scheduled_at'] ? 'scheduled' : 'draft',
        ]);

        return redirect()->route('campaigns.index');
    }

    public function show(Campaign $campaign): Response
    {
        $this->authorizeCampaign($campaign);

        $campaign->load('contactLists', 'sends');

        return Inertia::render('Campaigns/Show', [
            'campaign' => $campaign,
        ]);
    }

    public function edit(Campaign $campaign): Response
    {
        $this->authorizeCampaign($campaign);

        return Inertia::render('Campaigns/Edit', [
            'campaign' => $campaign,
        ]);
    }

    public function update(Request $request, Campaign $campaign)
    {
        $this->authorizeCampaign($campaign);

        if (!$campaign->isEditable()) {
            return back()->withErrors(['message' => 'Only draft or scheduled campaigns can be edited.']);
        }

        $validated = $request->validate([
            'name'         => 'sometimes|string|max:255',
            'subject'      => 'sometimes|string|max:255',
            'body'         => 'sometimes|string',
            'scheduled_at' => 'nullable|date|after:now',
        ]);

        $campaign->update($validated);

        return redirect()->route('campaigns.index');
    }

    public function destroy(Campaign $campaign)
    {
        $this->authorizeCampaign($campaign);

        if (!$campaign->isEditable()) {
            return back()->withErrors(['message' => 'Cannot delete a campaign that is currently sending.']);
        }

        $campaign->delete();

        return redirect()->route('campaigns.index');
    }

    private function authorizeCampaign(Campaign $campaign): void
    {
        if ($campaign->user_id !== auth()->id()) {
            abort(403, 'Unauthorized.');
        }
    }
}