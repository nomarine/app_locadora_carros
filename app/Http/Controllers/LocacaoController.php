<?php

namespace App\Http\Controllers;

use App\Models\Locacao;
use App\Http\Requests\StoreLocacaoRequest;
use App\Http\Requests\UpdateLocacaoRequest;
use App\Http\Requests\IndexLocacaoRequest;
use App\Repositories\LocacaoRepository;

use Illuminate\Support\Facades\Storage;

class LocacaoController extends Controller
{
    public function __construct(Locacao $locacao) {
        $this->locacao = $locacao;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(IndexLocacaoRequest $request)
    {
        $repositoryLocacao = new LocacaoRepository($this->locacao);

        if($request->has('atributos_cliente')){
            $atributos_cliente = 'cliente:id,'.$request->atributos_cliente;
        } else {
            $atributos_cliente = 'cliente'; 
        }
        if($request->has('atributos_carro')){
            $atributos_carro = 'carro:id,'.$request->atributos_carro;
        } else {
            $atributos_carro = 'carro'; 
        }
        if($request->has('atributos_modelo')){
            $atributos_modelo = 'carro.modelo:id,'.$request->atributos_modelo;            
        } else {
            $atributos_modelo = 'carro.modelo';
        }
        if($request->has('atributos_marca')){
            $atributos_marca = 'carro.modelo.marca:id,'.$request->atributos_marca;            
        } else {
            $atributos_marca = 'carro.modelo.marca';
        }
        $repositoryLocacao->selectAtributosRelacionamento([
            $atributos_cliente, 
            $atributos_carro, 
            $atributos_modelo, 
            $atributos_marca
        ]);

        if($request->has('filtros')){
            $repositoryLocacao->filtro($request->filtros);
        }

        if($request->has('atributos')){
            $atributos = $request->atributos;
            $repositoryLocacao->selectAtributos($atributos);
        }
        
        return response()->json($repositoryLocacao->getResultado(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLocacaoRequest $request)
    {
        $request->validate($this->locacao->regras());

        $locacao = $this->locacao->create($request->all());
        return $locacao;
    }

    /**
     * Display the specified resource.
     * @param Integer
     */
    
    public function show(IndexLocacaoRequest $request, $id)
    {
        $locacao = $this->locacao->find($id);
        if($locacao === null){
            return response('Recurso não encontrado. (Locacao)', 404);
        }

        $repositoryLocacao = new LocacaoRepository($this->locacao);

        if($request->has('atributos_cliente')){
            $atributos_cliente = 'cliente:id,'.$request->atributos_cliente;
        } else {
            $atributos_cliente = 'cliente'; 
        }
        if($request->has('atributos_carro')){
            $atributos_carro = 'carro:id,'.$request->atributos_carro;
        } else {
            $atributos_carro = 'carro'; 
        }
        if($request->has('atributos_modelo')){
            $atributos_modelo = 'carro.modelo:id,'.$request->atributos_modelo;            
        } else {
            $atributos_modelo = 'carro.modelo';
        }
        if($request->has('atributos_marca')){
            $atributos_marca = 'carro.modelo.marca:id,'.$request->atributos_marca;            
        } else {
            $atributos_marca = 'carro.modelo.marca';
        }
        $repositoryLocacao->selectAtributosRelacionamento([
            $atributos_cliente, 
            $atributos_carro, 
            $atributos_modelo, 
            $atributos_marca
        ]);

        $repositoryLocacao->filtro('id:=:'.$id);

        if($request->has('atributos')){
            $atributos = $request->atributos;
            $repositoryLocacao->selectAtributos($atributos);
        }
        
        return response()->json($repositoryLocacao->getResultado(), 200);
    }

    /**
     * Update the specified resource in storage.
     * @param Integer
     */
    public function update(UpdateLocacaoRequest $request, $id)
    {
        $locacao = $this->locacao->find($id);
        if($locacao === null){
            return response('Recurso não encontrado. (Locacao)', 404);
        }

        if($request->method() === 'PATCH'){
            $locacao->fill($request->all());
            $regrasDinamicas = array();

            foreach($locacao->regras() as $input => $regra){
                if(array_key_exists($input, $request->all())){
                    $regrasDinamicas[$input] = $regra;
                }
            }
            if(empty($regrasDinamicas)){
                return response('Nenhum campo informado. (Locacao)', 400);
            }

            $request->validate($regrasDinamicas);
        } else {
            $request->validate($locacao->regras());
        }
        
        $locacao->save();

        return $locacao;
    }

    /**
     * Remove the specified resource from storage.
     * @param Integer
     */
    public function destroy($id)
    {
        $locacao = $this->locacao->find($id);
        if($locacao === null){
            return response('Recurso não encontrado. (Locacao)', 404);
        }
        
        $locacao->delete();
        return [
            'cliente' => $locacao,
            'mensagem' => 'Recurso excluído com sucesso. (Locacao)'
        ];
    }
}
