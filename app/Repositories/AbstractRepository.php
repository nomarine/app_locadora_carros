<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository {
    public function __construct(Model $model){
        $this->model = $model;
    }

    public function selectAtributosRelacionamento($atributos){
        $this->model = $this->model->with($atributos);
    }

    public function filtro($parametros){
        $filtros = explode(';', $parametros);
        foreach($filtros as $key=>$item){   
            $filtro = explode(':', $item);
            $this->model = $this->model->where($filtro[0], $filtro[1], $filtro[2]);
        }
    }

    public function selectAtributos($atributos){
        $this->model = $this->model->selectRaw($atributos);
    }

    public function getResultado(){
        return $this->model->get();
    }

}

?>