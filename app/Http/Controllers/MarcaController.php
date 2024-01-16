<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Http\Requests\StoreMarcaRequest;
use App\Http\Requests\UpdateMarcaRequest;

use Illuminate\Support\Facades\Storage;

class MarcaController extends Controller
{
    public function __construct(Marca $marca) {
        $this->marca = $marca;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $marcas = $this->marca->all();
        return $marcas;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMarcaRequest $request)
    {
        $request->validate($this->marca->regras(), $this->marca->feedback());

        $urn_imagem = $request->imagem->store('imagens', 'public');

        $marca = $this->marca->create([
            'nome'=>$request->nome,
            'imagem'=>$urn_imagem
        ]);
        return $marca;
    }

    /**
     * Display the specified resource.
     * @param Integer
     */
    
    public function show($id)
    {
        $marca = $this->marca->find($id);
        if($marca === null){
            return response('Recurso não encontrado.', 404);
        }
        return $marca;
    }

    /**
     * Update the specified resource in storage.
     * @param Integer
     */
    public function update(UpdateMarcaRequest $request, $id)
    {
        $marca = $this->marca->find($id);
        if($marca === null){
            return response('Recurso não encontrado.', 404);
        }

        if($request->method() === 'PATCH'){
            $regrasDinamicas = array();

            foreach($marca->regras() as $input => $regra){
                if(array_key_exists($input, $request->all())){
                    $regrasDinamicas[$input] = $regra;
                }
            }
            if(empty($regrasDinamicas)){
                return response('Nenhum campo informado.', 400);
            }

            $request->validate($regrasDinamicas, $marca->feedback());
        } else {
            $request->validate($marca->regras(), $marca->feedback());
        }
        
        if($request->imagem){
            Storage::disk('public')->delete($marca->imagem);
            $urn_imagem = $request->imagem->store('imagens', 'public');
        }

        $marca->imagem = $urn_imagem ?? $marca->imagem;
        $marca->nome = $request->nome ?? $marca->nome;
        
        $marca->save();
        return $marca;
    }

    /**
     * Remove the specified resource from storage.
     * @param Integer
     */
    public function destroy($id)
    {
        $marca = $this->marca->find($id);
        if($marca === null){
            return response('Recurso não encontrado.', 404);
        }
        Storage::disk('public')->delete($marca->imagem);
        $marca->delete();
        return [
            'marca' => $marca,
            'mensagem' => 'Marca excluída com sucesso.'
        ];
    }
}
