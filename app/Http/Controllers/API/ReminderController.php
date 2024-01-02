<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiController;
use App\Models\Reminder;
use Illuminate\Http\Request;

class ReminderController extends ApiController
{

    public function index(Request $request)
    {

        $validator = \Validator::make($request->all(), [
            'limit' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return $this->responseFailed($validator->errors(), 422);
        }

        $validated = $validator->validate();
        $limit = isset($validated['limit']) ? $validated['limit'] : 10;

        $query = \DB::table('reminders')->select([
            'id',
            'title',
            'description',
            'event_at',
            'remind_at',
        ]);

        if ($limit !== '') {
            $query->limit($limit);
        }

        $reminders = $query->get();

        return $this->responseSuccess([
            'reminders' => $reminders,
            'limit' => $limit,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'title' => 'required|string|min:3,max:50',
            'description' => 'nullable|string',
            'event_at' => 'integer',
            'remind_at' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return $this->responseFailed($validator->errors(), 422);
        }

        $validated = $validator->validate();

        // check if title and event_at already exists
        $check = Reminder::where('title', $validated['title'])
            ->where('event_at', $validated['event_at'])
            ->count();

        if ($check > 0) {
            return $this->responseFailed('Reminder with this title and schedule is already exists!', 422);
        }

        $reminderCreated = Reminder::create([
            'user_id' => 1,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'event_at' => $validated['event_at'],
            'remind_at' => $validated['remind_at'],
        ]);

        return $this->responseSuccess($reminderCreated, 201);
    }

    public function show($id)
    {
        // check reminder
        $reminder = Reminder::find($id);
        if (!$reminder) {
            return $this->responseFailed('resource is not found', 404);
        }

        return $this->responseSuccess($reminder, 200);
    }

    public function update(Request $request, $id)
    {
        // check reminder
        $reminder = Reminder::find($id);
        if (!$reminder) {
            return $this->responseFailed('resource is not found', 404);
        }

        $validator = \Validator::make($request->all(), [
            'title' => 'required|string|min:3,max:50',
            'description' => 'nullable|string',
            'event_at' => 'integer',
            'remind_at' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return $this->responseFailed($validator->errors(), 422);
        }

        $validated = $validator->validate();

        // check if title and event_at already exists
        $check = Reminder::where('title', $validated['title'])
            ->where('event_at', $validated['event_at'])
            ->whereNotIn('id', [$id])->count();

        if ($check > 0) {
            return $this->responseFailed('Reminder with this title and schedule is already exists!', 422);
        }

        $reminder->update([
            'user_id' => 1,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'event_at' => $validated['event_at'],
            'remind_at' => $validated['remind_at'],
        ]);

        $reminderUpdated = $reminder->refresh();

        return $this->responseSuccess($reminderUpdated, 200);
    }

    public function destroy($id)
    {
        // check reminder
        $reminder = Reminder::find($id);
        if (!$reminder) {
            return $this->responseFailed('resource is not found', 404);
        }

        $reminder->delete();

        return $this->responseSuccess($reminder, 200);
    }
}
