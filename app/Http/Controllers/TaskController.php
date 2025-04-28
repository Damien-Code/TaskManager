<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Models\TaskCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return Inertia::render('Tasks/Index', [
            'tasks' => Task::query()
                ->with(['media', 'taskCategories'])
                ->when($request->has('categories'), function ($query) use ($request) {
                    $query->whereHas('taskCategories', function ($query) use ($request) {
                        $query->whereIn('id', $request->query('categories'));
                    });
                })->paginate(15)
                ->withQueryString(),
            'categories' => TaskCategory::whereHas('tasks')->withCount('tasks')->get(),
            'selectedCategories' => $request->query('categories'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Tasks/Create',
        [
            'categories' => TaskCategory::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        //Task::create($request->validated() + ['is_completed' => false]);

        //$task = Task::create($request->validated() + ['is_completed' => false]);
        $task = Task::create($request->safe(['name', 'due_date']) + ['is_completed' => false]);
        if ($request->hasFile('media')){
            $task->addMedia($request->file('media'))->toMediaCollection();
        }
        if ($request->has('categories')) {
            $task->taskCategories()->sync($request->validated('categories'));
        }
        return redirect()->route('tasks.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        $task->load('media', 'taskCategories');
        $task->append('mediaFile');
        return Inertia::render('Tasks/Edit', [
            'task' => $task,
            'categories' => TaskCategory::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->update($request->validated());

        if ($request->hasFile('media')) {
            $task->getFirstMedia()?->delete();
            $task->addMedia($request->file('media'))->toMediaCollection();
        }

        $task->taskCategories()->sync($request->validated('categories', []));
        return redirect()->route('tasks.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index');
    }
}
