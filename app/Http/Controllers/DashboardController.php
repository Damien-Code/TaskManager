<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('Dashboard', [
            'completedVsPendingTaskChart' => $this->getCompletedVsPendingTaskChart(),
            'pendingTasksToday' => Task::query()
                ->where('is_completed', false)
                ->whereDate('due_date', now())
                ->where('user_id', auth()->id())
                ->count(),
            'tasksCreatedByDay' => $this->getTasksCreatedByDay(),
        ]);
    }

    private function getCompletedVsPendingTaskChart(): array
    {
        return [
            'labels' => ['Completed', 'In Progress'],
            'datasets' => [
                [
                    'label' => 'Tasks',
                    'backgroundColor' => ['#000000', '#d3d3d3'],
                    'data' => [
                        Task::query()->where('is_completed', true)->where('user_id', auth()->id())->count(),
                        Task::query()->where('is_completed', false)->where('user_id', auth()->id())->count(),
                    ],
                ],
            ],
        ];
    }

    private function getTasksCreatedByDay()
    {
        return [
            'labels' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            'datasets' => [
                [
                    'label' => 'Tasks',
                    'backgroundColor' => '#d3d3d3',
                    'data' => collect(range(0, 6))
                        ->map(function ($day) {
                            $date = now()->startOfWeek()->addDays($day);

                            return Task::query()->whereDate('created_at', $date)->where('user_id', auth()->id())->count();
                        })
                        ->toArray(),
                ],
            ],
        ];
    }
}
