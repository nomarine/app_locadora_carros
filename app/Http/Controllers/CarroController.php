<?php

namespace App\Http\Controllers;

use App\Models\Carro;
use App\Http\Requests\StoreCarroRequest;
use App\Http\Requests\UpdateCarroRequest;
use App\Http\Requests\IndexCarroRequest;
use App\Repositories\CarroRepository;

use Illuminate\Support\Facades\Storage;

class CarroController extends Controller
{
    public function __construct(Carro $carro) {
        $this->carro = $carro;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(IndexCarroRequest $request)
    {
        $repositoryCarro = new CarroRepository($this->carro);

        if($request->has('atributos_modelo')){
            $atributos_modelo = 'modelo:id,'.$request->atributos_modelo;
        } else {
            $atributos_modelo = 'modelo'; 
         }
        if($request->has('atributos_marca')){
            $atributos_marca = 'modelo.marca:id,'.$request->atributos_marca;            
        } else {
            $atributos_marca = 'modelo.marca';
        }
        $repositoryCarro->selectAtributosRelacionamento([$atributos_modelo, $atributos_marca]);

        if($request->has('filtros')){
            $repositoryCarro->filtro($request->filtros);
        }

        if($request->has('atributos')){
            $atributos = $request->atributos;
            $repositoryCarro->selectAtributos($atributos);
        }
        
        return response()->json($repositoryCarro->getResultado(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCarroRequest $request)
    {
        $request->validate($this->carro->regras());

        $carro = $this->carro->create($request->all());
        return $carro;
    }

    /**
     * Display the specified resource.
     * @param Integer
     */
    
    public function show(IndexCarroRequest $request, $id)
    {
        $carro = $this->carro->with('modelo')->find($id);
        if($carro === null){
            return response('Recurso não encontrado. (Carro)', 404);
        }

        $repositoryCarro = new CarroRepository($this->carro);

        if($request->has('atributos_modelo')){
            $atributos_modelo = 'modelo:id,'.$request->atributos_modelo;
        } else {
            $atributos_modelo = 'modelo'; 
         }
        if($request->has('atributos_marca')){
            $atributos_marca = 'modelo.marca:id,'.$request->atributos_marca;            
        } else {
            $atributos_marca = 'modelo.marca';
        }
        $repositoryCarro->selectAtributosRelacionamento([$atributos_modelo, $atributos_marca]);

        if($request->has('filtros')){
            $repositoryCarro->filtro($request->filtros);
        }

        if($request->has('atributos')){
            $atributos = $request->atributos;
            $repositoryCarro->selectAtributos($atributos);
        }
        
        return response()->json($repositoryCarro->getResultado(), 200);
    }

    /**
     * Update the specified resource in storage.
     * @param Integer
     */
    public function update(UpdateCarroRequest $request, $id)
    {
        $carro = $this->carro->find($id);
        if($carro === null){
            return response('Recurso não encontrado. (Carro)', 404);
        }

        if($request->method() === 'PATCH'){
            $carro->fill($request->all());
            $regrasDinamicas = array();

            foreach($carro->regras() as $input => $regra){
                if(array_key_exists($input, $request->all())){
                    $regrasDinamicas[$input] = $regra;
                }
            }
            if(empty($regrasDinamicas)){
                return response('Nenhum campo informado.', 400);
            }

            $request->validate($regrasDinamicas);
        } else {
            $request->validate($carro->regras());
        }
        
        $carro->save();
        return $carro;
    }

    /**
     * Remove the specified resource from storage.
     * @param Integer
     */
    public function destroy($id)
    {
        $carro = $this->carro->find($id);
        if($carro === null){
            return response('Recurso não encontrado. (Carro)', 404);
        }
        
        $carro->delete();
        return [
            'Carro' => $carro,
            'mensagem' => 'Recurso excluído com sucesso. (Carro)'
        ];
    }
}
