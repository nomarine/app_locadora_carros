<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use App\Http\Requests\IndexClienteRequest;
use App\Repositories\ClienteRepository;

use Illuminate\Support\Facades\Storage;

class ClienteController extends Controller
{
    public function __construct(Cliente $cliente) {
        $this->cliente = $cliente;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(IndexClienteRequest $request)
    {
        $repositoryCliente = new ClienteRepository($this->cliente);

        if($request->has('filtros')){
            $repositoryCliente->filtro($request->filtros);
        }

        if($request->has('atributos')){
            $atributos = $request->atributos;
            $repositoryCliente->selectAtributos($atributos);
        }
        
        return response()->json($repositoryCliente->getResultado(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClienteRequest $request)
    {
        $request->validate($this->cliente->regras());

        $cliente = $this->cliente->create($request->all());
        return $cliente;
    }

    /**
     * Display the specified resource.
     * @param Integer
     */
    
    public function show(IndexClienteRequest $request, $id)
    {
        $cliente = $this->cliente->find($id);
        if($cliente === null){
            return response('Recurso não encontrado. (Cliente)', 404);
        }

        $repositoryCliente = new ClienteRepository($this->cliente);

        $repositoryCliente->filtro('id:=:'.$id);

        if($request->has('atributos')){
            $atributos = $request->atributos;
            $repositoryCliente->selectAtributos($atributos);
        }
        
        return response()->json($repositoryCliente->getResultado(), 200);
    }

    /**
     * Update the specified resource in storage.
     * @param Integer
     */
    public function update(UpdateClienteRequest $request, $id)
    {
        $cliente = $this->cliente->find($id);
        if($cliente === null){
            return response('Recurso não encontrado. (Cliente)', 404);
        }

        if($request->method() === 'PATCH'){
            $cliente->fill($request->all());
            $regrasDinamicas = array();

            foreach($cliente->regras() as $input => $regra){
                if(array_key_exists($input, $request->all())){
                    $regrasDinamicas[$input] = $regra;
                }
            }
            if(empty($regrasDinamicas)){
                return response('Nenhum campo informado.', 400);
            }

            $request->validate($regrasDinamicas);
        } else {
            $request->validate($cliente->regras());
        }
        
        $cliente->save();

        return $cliente;
    }

    /**
     * Remove the specified resource from storage.
     * @param Integer
     */
    public function destroy($id)
    {
        $cliente = $this->cliente->find($id);
        if($cliente === null){
            return response('Recurso não encontrado. (Cliente)', 404);
        }
        
        $cliente->delete();
        return [
            'cliente' => $cliente,
            'mensagem' => 'Recurso excluído com sucesso. (Cliente)'
        ];
    }
}
