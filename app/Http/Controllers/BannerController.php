<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Banner::all();
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
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:1,0',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validation->errors()->first(),
            ], 400);
        }

        $image = $request->file('image');
        $image_name = time() . '.' . $image->extension();
        $image->move(public_path('images'), $image_name);

        $banner = Banner::create([
            'image' => $image_name,
            'status' => $request->status,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Banner created successfully',
            'data' => $banner,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Banner::findOrFail($id);
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
        $validation = Validator::make(['id' => $id], [
            'id' => 'required|exists:banners,id',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validation->errors()->first(),
            ], 400);
        }

        $banner = Banner::find($id);

        // Caso tenha enviado image
        if (request()->hasFile('image')) {
            $validation = Validator::make(request()->all(), [
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($validation->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validation->errors()->first(),
                ], 400);
            }

            $image = request()->file('image');
            $image_name = time() . '.' . $image->extension();
            $image->move(public_path('images'), $image_name);

            $banner->image = $image_name;
        }

        // Caso tenha enviado status
        if (request()->has('status')) {
            $validation = Validator::make(request()->all(), [
                'status' => 'required|in:1,0',
            ]);

            if ($validation->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validation->errors()->first(),
                ], 400);
            }

            $banner->status = request()->status;
        }

        $banner->save();

        return response()->json([
            'status' => 'success',
            'data' => $banner,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $validation = Validator::make(['id' => $id], [
            'id' => 'required|exists:banners,id',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validation->errors()->first(),
            ], 400);
        }

        // Deletar image
        $banner = Banner::find($id);
        
        if ($banner->image) {
            unlink(public_path('images/' . $banner->image));
        }

        Banner::destroy($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Banner deleted successfully',
        ]);
    }
}
