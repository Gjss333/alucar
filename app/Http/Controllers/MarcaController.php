<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\Request;

class MarcaController extends Controller
{

    //injeção de instancia no model
    public function __construct(Marca $marca)
    {
        $this->marca = $marca;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$marca = Marca::all();

        $marcas = $this->marca->all();
        return response()->json($marcas, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $marca = Marca::create($request->all());
        $marcas = $this->marca->create($request->all());
        return response()->json($marcas, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $marcas = $this->marca->find($id);

        if($marcas === null){
            return response()->json(['msg' => 'Recurso pesquisado não existe'], 404);
        }
        
        return response()->json($marcas, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // $marca->update($request->all());
        $marcas = $this->marca->find($id);
        
        if($marcas === null){
            return response()->json(['msg' => 'impossível realizar a atualização do registro'], 404);
        }
        
        $marcas->update($request->all());
        return response()->json($marcas, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // $marca->delete();
        $marcas = $this->marca->find($id);
          
        if($marcas === null){
            return response()->json(['msg' => 'impossível a remoção do registro'], 404);
        }
        
        $marcas->delete();
        return response(['msg' => 'removida com sucesso'], 200);
    }
}
