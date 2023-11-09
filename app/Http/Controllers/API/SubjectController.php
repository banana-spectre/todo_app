<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Subject;
use App\Models\TodoItem;
use App\Models\File;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Used for Main View/Home Page
        //Return all to do subject and its to do items

        $todo_list = Subject::with(['todo_items', 'files'])
            ->get();
        return response()->json($todo_list);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate ([
            'subject' => 'required|max:255'
        ]);

        $subject = New Subject();
        $subject->subject_name = $validated['subject'];
        $subject->save();

        return response()->json($subject);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $subject = Subject::with(['todo_items', 'files'])
            ->where('id', $id)
            ->firstOrFail();
        return response()->json($subject);;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate ([
            'subject_name' => 'required|max:255'
        ]);

        $subject = Subject::where('id', $id)->firstOrFail();
        $subject->update($request->all());

        return response()->json($subject);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $subject = Subject::where('id', $id)->firstOrFail();
        $subject->delete();
        
        return response()->json(['message' => 'Record deleted.']);
    }
}
