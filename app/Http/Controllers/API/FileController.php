<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\File;

class FileController extends Controller
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
        $validated = $request->validate([
            'files' => 'required|mimes:jpg,png,pdf,xlsx,xls',
            'subject_id' => 'required'
        ]);

        if($request->hasfile('files'))
        {
            $create['file_name'] = $validated['files']->getClientOriginalName();
            $create['file_path'] = $validated['files']->move('storage/app/public/files');
            $create['subject_id'] = $validated['subject_id'];
        }
        File::create($create);

        return response()->json(['message' => 'Successfully uploaded file.']);
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
       //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $file = File::where('id', $id)->firstOrFail();
        $file->delete();

        return response()->json(['message' => 'Record deleted.']);
    }
}
