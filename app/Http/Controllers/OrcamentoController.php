<?php

namespace App\Http\Controllers;

use App\Models\Orcamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrcamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Orcamento::all();
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
            'nome' => 'required|string',
            'email' => 'required|email',
            'mensagem' => 'required|string',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validation->errors()->first(),
            ], 400);
        }

        $orcamento = Orcamento::create([
            'nome' => $request->nome,
            'email' => $request->email,
            'mensagem' => $request->mensagem,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Orcamento created successfully',
            'data' => $orcamento,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Orcamento::findOrFail($id);
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
            'nome' => 'required|string',
            'email' => 'required|email',
            'mensagem' => 'required|string',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validation->errors()->first(),
            ], 400);
        }

        $orcamento = Orcamento::findOrFail($id);

        $orcamento->update([
            'nome' => $request->nome,
            'email' => $request->email,
            'mensagem' => $request->mensagem,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Orcamento updated successfully',
            'data' => $orcamento,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $orcamento = Orcamento::findOrFail($id);

        $orcamento->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Orcamento deleted successfully',
        ]);
    }
}
