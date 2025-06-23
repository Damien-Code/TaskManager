<?php

namespace App\Http\Controllers;

use App\Models\Standup;
use App\Models\User;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class StandupController extends Controller
{
    public function index(Request $request)
    {
        $selectedTeam = $request->query('team');
        $selectedDate = $request->query('date');

        return Inertia::render('Standup/Index', [
            'teams' => $request->user()->teams,
            'standups' => Standup::query()
                ->with('user')
                ->when($selectedTeam, fn($query) =>
                $query->where('team_id', $selectedTeam)
                )
                ->when($selectedDate, fn($query) =>
                $query->whereDate('date', Carbon::parse($selectedDate))
                )
                ->orderBy('date')
                ->get(),
            'date' => $request->has('date')
                ? Carbon::parse($request->query('date'))->format('Y-m-d')
                : now()->format('Y-m-d'),
            'selectedTeam' => $selectedTeam,
            'users' => User::whereHas('teams', function ($query) use ($selectedTeam) {
                $query->where('team_id', $selectedTeam);
            })
            ->with('teams')
            ->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'accomplishment' => 'required',
            'doing' => 'required',
            'reflection' => 'required',
            'date' => 'required|date',
            'team_id' => 'required|exists:teams,id'
        ]);
        $validated['date'] = Carbon::parse($validated['date']);
        auth()->user()->standups()->create($validated);
        return redirect()->route('daily-standup.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Standup $standup, Team $team)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Standup $standup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Standup $standup)
    {
        dd($request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Standup $standup)
    {
        //
    }
}
