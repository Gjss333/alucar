<?php

namespace App\Http\Controllers;

use App\Models\Marca;
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
        return $marcas;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $marca = Marca::create($request->all());
        $marcas = $this->marca->create($request->all());
        return $marcas;
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $marcas = $this->marca->find($id);

        if($marcas === null){
            return ['msg' => 'Recurso pesquisado não existe'];
        }
        
        return $marcas;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // $marca->update($request->all());
        $marcas = $this->marca->find($id);
        
        if($marcas === null){
            return ['msg' => 'impossível realizar a atualização do registro'];
        }
        
        $marcas->update($request->all());
        return $marcas;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // $marca->delete();
        $marcas = $this->marca->find($id);
          
        if($marcas === null){
            return ['msg' => 'impossível a remoção do registro'];
        }
        
        $marcas->delete();
        return ['msg' => 'removida com sucesso'];
    }
}
