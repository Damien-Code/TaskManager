<?php

namespace App\Http\Controllers;

use App\Models\Standup;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class StandupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
//        if ($request->has('date'))
//            dd($request->query('date'), Carbon::parse($request->query('date')));
        return Inertia::render('Standup/Index', [
            'standups' => Standup::query()
                ->with('user')
                ->when($request->has('date'), function ($query) use ($request) {
                    $query->whereDate('date', Carbon::parse($request->query('date')));
                })->orderBy('date')->get(),
            'date' => Carbon::parse($request->query('date'))->format('Y-m-d'),
            'users' => User::all()
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
        ]);
        $validated['date'] = Carbon::parse($validated['date']);
        auth()->user()->standups()->create($validated);
        return redirect()->route('daily-standup.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Standup $standup)
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Standup $standup)
    {
        //
    }
}
