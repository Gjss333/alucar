<?php

namespace App\Http\Controllers;


use App\Models\Marca;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
    public function index(Request $request)
    {
        $marca = array();

        if($request->has('atributos_modelos')){
            $atributos_modelos = $request->atributos_modelos;
            $marca =  $this->marca->with('modelos:id,'.$atributos_modelos);
        } else{
            $marca = $this->marca->with('modelos');
        }

        if($request->has('filtro')){

            $filtros = explode(';', $request->filtro);
            foreach($filtros as $key => $condicao ){
                
                $c = explode(':', $condicao);
                $marca = $marca->where($c[0], $c[1],$c[2]);
            }

        }

        if($request->has('atributos')){
            // dd($request->atributos);
            $atributos = $request->atributos;
            $marca = $marca->selectRaw($atributos)->get();
            // dd($atributos_marcas);

        } else{
            $marca = $marca->get();
        }
        
        return response()->json($this->marca->with('modelos')->get(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $marca = Marca::create($request->all());
       
        $request->validate($this->marca->rules(), $this->marca->feedback());

        $imagem = $request->imagem;
        $imagem_urn = $imagem->store('imagens', 'public');

        $marcas = $this->marca->create([
            'nome' => $request->nome,
            'imagem' => $imagem_urn
        ]);

        return response()->json($marcas, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $marcas = $this->marca->with('modelos')->find($id);

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

        if($request->method() === 'PATCH'){

            $regrasDinamicas = array();

            foreach($marcas->rules() as $input => $regra){

                if(array_key_exists($input, $request->all())){
                     $regrasDinamicas[$input] = $regra;
                }
            }

            $request->validate($regrasDinamicas);
        }else{

            $request->validate($marcas->rules(), $marcas->feedback());
        }
        
        //remove um antigo caso um nove esteja enviado no request
        if($request->imagem){
            Storage::disk('public')->delete($marcas->imagem);
        }

        $imagem = $request->imagem;
        $imagem_urn = $imagem->store('imagens', 'public');
        
        $marcas->fill($request->all());
        $marcas->imagem = $imagem_urn;
        $marcas->save();
        
        $marcas->update([
            'nome' => $request->nome,
            'imagem' => $imagem_urn
        ]);   
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

        Storage::disk('public')->delete($marcas->imagem);
        
        $marcas->delete();
        return response(['msg' => 'removida com sucesso'], 200);
    }
}
