<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Http\Requests\ScheduleRequest;
use App\Models\CoverArea;
use App\Models\Shift;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class ScheduleController
 * @package App\Http\Controllers
 */
class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $schedules = Schedule::paginate();
        $this->authorize("viewAny", Schedule::class);

        return view('schedule.index', compact('schedules'))
            ->with('i', (request()->input('page', 1) - 1) * $schedules->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $schedule = new Schedule();
        $users = User::all();
        $shifts = Shift::all();
        $areas = CoverArea::all();
        $this->authorize("create", Schedule::class);

        return view('schedule.create', compact('schedule', 'users', 'shifts', 'areas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ScheduleRequest $request)
    {
        $this->authorize("create", Schedule::class);
        Schedule::create($request->validated());

        return redirect()->route('schedules.index')
            ->with('success', 'Schedule created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $schedule = Schedule::find($id);
        $this->authorize("view", Schedule::class);

        return view('schedule.show', compact('schedule'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $schedule = Schedule::find($id);
        $users = User::all();
        $shifts = Shift::all();
        $areas = CoverArea::all();
        $this->authorize("update", $schedule);

        return view('schedule.edit', compact('schedule', 'users', 'shifts', 'areas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ScheduleRequest $request, Schedule $schedule)
    {
        $schedule->update($request->validated());
        $this->authorize("update", $schedule);

        return redirect()->route('schedules.index')
            ->with('success', 'Schedule updated successfully');
    }

    public function destroy($id)
    {
        $schedule = Schedule::find($id);
        $this->authorize("delete", $schedule);
        $schedule->delete();


        return response()->json($schedule);
    }

    public function list()
    {
        $schedule = Schedule::with('coverArea', 'user', 'shift')->get();

        return DataTables::of($schedule)->make(true);
    }

    public function getEvents()
    {
        $all_Events = Schedule::with('coverArea', 'user', 'shift')->get();
        $events = [];

        foreach ($all_Events as $event) {
            // $startDateTime = $event->begin . ' ' . $event->shift->begin;
            // $endDateTime = $event->end . ' ' . $event->shift->end;
            $events[] = [
                'title' => $event->user->name,
                'startTime' => $event->shift->begin,
                'endTime' => $event->shift->end,
                'startRecur' => $event->begin,
                'endRecur' => $event->end,
                'backgroundColor' => $event->coverArea->color,
                'borderColor' => $event->coverArea->color,
            ];
        }

        return response()->json($events);
    }
}
