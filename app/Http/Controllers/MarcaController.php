<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Http\Requests\StoreMarcaRequest;
use App\Http\Requests\UpdateMarcaRequest;

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
        $regras = [
            'nome' => 'required|unique:marcas',
            'imagem' => 'required'
        ];
        $feedback = [
            'required' => 'O campo :attribute não foi informado.',
            'unique' => 'O valor do campo :attribute já existe.',
        ];
        $request->validate($regras, $feedback);

        $marca = $this->marca->create($request->all());
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
        $marca->update($request->all());
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
        $marca->delete();
        return [
            'marca' => $marca,
            'mensagem' => 'Marca excluída com sucesso.'
        ];
    }
}
