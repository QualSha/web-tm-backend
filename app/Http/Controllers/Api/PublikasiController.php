<?php
namespace App\Http\Controllers\Api;

use App\Models\Publikasi; 
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PublikasiController extends Controller
{
    public function index()
    {
        return response()->json(Publikasi::latest()->get());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'releaseDate' => 'required|date',
            'description' => 'nullable|string',
            'coverUrl' => 'nullable|url',
        ]);
        if ($validator->fails()) return response()->json($validator->errors(), 422);
        $publikasi = Publikasi::create($validator->validated());
        return response()->json($publikasi, 201);
    }

    public function show(Publikasi $publikasi) 
    {
        return response()->json($publikasi);
    }

    public function update(Request $request, Publikasi $publikasi) // <-- Diubah
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'releaseDate' => 'required|date',
            'description' => 'nullable|string',
            'coverUrl' => 'nullable|url',
        ]);
        if ($validator->fails()) return response()->json($validator->errors(), 422);
        $publikasi->update($validator->validated());
        return response()->json($publikasi);
    }

    public function destroy(Publikasi $publikasi) // <-- Diubah
    {
        $publikasi->delete();
        return response()->json(['message' => 'Publikasi berhasil dihapus']);
    }
}