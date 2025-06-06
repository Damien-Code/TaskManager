<?php

namespace App\Http\Controllers;

use App\Models\Standup;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StandupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Standup/Index', [
            'standup' => Standup::all(),
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
        //
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
