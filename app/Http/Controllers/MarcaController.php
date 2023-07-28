<?php

namespace App\Http\Controllers;


use App\Models\Marca;
use App\Repositories\MarcaRepository;
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

        $marcaRepository = new MarcaRepository($this->marca);

        if($request->has('atributos_modelos')){
            $atributos_modelos = 'modelos:id,'.$request->atributos_modelos;
            $marcaRepository->selectAtributosRegistrosRelacionados($atributos_modelos);
        } else{
            $marcaRepository->selectAtributosRegistrosRelacionados('modelos');
        }

        if($request->has('filtro')){
            $marcaRepository->filtros($request->filtro);
        }

        if($request->has('atributos')){
           $marcaRepository->selectAtributos($request->atributos);

        }

        return response()->json($marcaRepository->getResultado(), 200);
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
