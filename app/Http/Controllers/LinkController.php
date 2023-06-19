<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Link::all();
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
        $validation = Validator::make($request->all(), [
            'title' => 'required|string',
            'url' => 'required|url',
            'status' => 'required|in:1,0',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Link::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validation = Validator::make($request->all(), [
            'title' => 'required|string',
            'url' => 'required|url',
            'status' => 'required|in:1,0',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validation->errors()->first(),
            ], 400);
        }

        $link = Link::findOrFail($id);

        $link->update([
            'title' => $request->title,
            'url' => $request->url,
            'status' => $request->status,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Link updated successfully',
            'data' => $link,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $link = Link::findOrFail($id);
        $link->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Link deleted successfully',
        ]);
    }
}
