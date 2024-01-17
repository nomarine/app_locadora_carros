<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use App\Http\Requests\StoreModeloRequest;
use App\Http\Requests\UpdateModeloRequest;
use App\Http\Requests\IndexModeloRequest;

use Illuminate\Support\Facades\Storage;

class ModeloController extends Controller
{
    public function __construct(Modelo $modelo){
        $this->modelo = $modelo;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(IndexModeloRequest $request)
    {
        if($request->has('atributos_marca')){
            $modelos = $this->modelo->with('marca:id,'.$request->atributos_marca);
        } else {
            $modelos = $this->modelo->with('marca');
        }

        if($request->has('atributos')){
            $modelos = $modelos->selectRaw($request->atributos)->get();
        } else {
            $modelos = $modelos->get();
        }

        return $modelos;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreModeloRequest $request)
    {
        $request->validate($this->modelo->regras());

        $urn_imagem = $request->imagem->store('imagens/modelos', 'public');

        $modelo = $this->modelo->create([
            'nome'=>$request->nome,
            'imagem'=>$urn_imagem,
            'numero_portas'=>$request->numero_portas, 
            'lugares'=>$request->lugares, 
            'air_bag'=>$request->air_bag, 
            'abs'=>$request->abs,
            'marca_id'=>$request->marca_id
        ]);
        return $modelo;
    }

    /**
     * Display the specified resource.
     * @param Integer
     */
    public function show($id)
    {
        $modelo = $this->modelo->with('marca')->find($id);
        if($modelo === null){
            return response('Recurso não encontrado. (Modelo)', 404);
        }
        return $modelo;
    }

    /**
     * Update the specified resource in storage.
     * @param Integer
     */
    public function update(UpdateModeloRequest $request, $id)
    {
        $modelo = $this->modelo->find($id);
        if($modelo === null){
            return response('Recurso não encontrado. (Modelo)', 404);
        }

        if($request->method() === 'PATCH'){
            $regrasDinamicas = array();

            foreach($modelo->regras() as $input => $regra){
                if(array_key_exists($input, $request->all())){
                    $regrasDinamicas[$input] = $regra;
                }
            }
            if(empty($regrasDinamicas)){
                return response('Nenhum campo informado.', 400);
            }

            $request->validate($regrasDinamicas);
        } else {
            $request->validate($modelo->regras());
        }
        
        if($request->imagem){
            Storage::disk('public')->delete($modelo->imagem);
            $urn_imagem = $request->imagem->store('imagens/modelos', 'public');
        }

        $modelo->imagem = $urn_imagem ?? $modelo->imagem;
        $modelo->nome = $request->nome ?? $modelo->nome;
        $modelo->numero_portas = $request->numero_portas ?? $modelo->numero_portas;
        $modelo->lugares = $request->lugares ?? $modelo->lugares;
        $modelo->air_bag = $request->air_bag ?? $modelo->air_bag;
        $modelo->abs = $request->abs ?? $modelo->abs;
        $modelo->marca_id = $request->marca_id ?? $modelo->marca_id;
        
        $modelo->save();
        return $modelo;
    }

    /**
     * Remove the specified resource from storage.
     * @param Integer
     */
    public function destroy($id)
    {
        $modelo = $this->modelo->find($id);
        if($modelo === null){
            return response('Recurso não encontrado. (Modelo)', 404);
        }
        Storage::disk('public')->delete($modelo->imagem);
        $modelo->delete();
        return [
            'modelo' => $modelo,
            'mensagem' => 'Recurso excluído com sucesso. (Modelo)'
        ];
    }
}
