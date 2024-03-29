<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'imagem'];

    public function regras() {
        return [
            'nome' => 'required|unique:marcas,nome,'.$this->id.'|min:3',
            'imagem' => 'required|file|mimes:png,jpeg'
        ];
    }

    public function feedback(){
        return [
            'required' => 'O campo :attribute não foi informado.',
            'unique' => 'O valor do campo :attribute já existe.',
            'min' => 'O campo :attribute deve ter pelo menos :min caracteres.',
            'mimes' => 'A imagem deve ser de um dos seguintes formatos: :values.'
        ];
    }

    public function modelos() {
        return $this->hasMany('App\Models\Modelo');
    }
}
