<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Http\Requests\StoreMarcaRequest;
use App\Http\Requests\UpdateMarcaRequest;
use App\Http\Requests\IndexMarcaRequest;

use Illuminate\Support\Facades\Storage;

class MarcaController extends Controller
{
    public function __construct(Marca $marca) {
        $this->marca = $marca;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(IndexMarcaRequest $request)
    {
        if($request->atributos){
            $marcas = $this->marca->with('modelos')->selectRaw($request->atributos)->get();
        } else {
            $marcas = $this->marca->with('modelos')->get();
        }
        
        return $marcas;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMarcaRequest $request)
    {
        $request->validate($this->marca->regras(), $this->marca->feedback());

        $urn_imagem = $request->imagem->store('imagens/marcas', 'public');

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
        $marca = $this->marca->with('modelos')->find($id);
        if($marca === null){
            return response('Recurso não encontrado. (Marca)', 404);
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
            return response('Recurso não encontrado. (Marca)', 404);
        }

        if($request->method() === 'PATCH'){
            $marca->fill($request->all());
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
        
        if($request->file('imagem')){
            Storage::disk('public')->delete($marca->imagem);
            $imagem = $request->file('imagem');
            $urn_imagem = $imagem->store('imagens/marcas', 'public');
            $marca->imagem = $urn_imagem;
        }
        
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
            return response('Recurso não encontrado. (Marca)', 404);
        }
        Storage::disk('public')->delete($marca->imagem);
        $marca->delete();
        return [
            'marca' => $marca,
            'mensagem' => 'Recurso excluído com sucesso. (Marca)'
        ];
    }
}
