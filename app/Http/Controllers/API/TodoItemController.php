<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\TodoItem;
use App\Models\File;

class TodoItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate ([
            'todo_name' => 'required|string|max:255',
            'subject_id' => 'required'
        ]);
        TodoItem::create([
            'todo_name' => $validated['todo_name'],
            'subject_id' => $validated['subject_id']
        ]);

        return response()->json($validated);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate ([
            'todo_name' => 'string|max:255',
            'is_complete' => 'boolean'
        ]);

        $todo_item = TodoItem::where('id', $id)->firstOrFail();
        $todo_item->update($request->all());

        return response()->json($todo_item);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $todo_item = TodoItem::where('id', $id)->firstOrFail();
        $todo_item->delete();

        return response()->json(['message' => 'Record deleted.']);
    }
}
