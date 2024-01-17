<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome', 
        'imagem', 
        'numero_portas', 
        'lugares', 
        'air_bag', 
        'abs',
        'marca_id'
    ];

    public function regras() {
        return [
            'nome' => 'required|unique:modelos,nome,'.$this->id.'|min:3',
            'imagem' => 'required|file|mimes:png,jpeg,jpg',
            'numero_portas' => 'required|integer|digits_between:1,5',
            'lugares' => 'required|integer|digits_between:1,20', 
            'air_bag' => 'required|bool',
            'abs' =>  'required|bool',
            'marca_id' => 'required|exists:marcas,id'
        ];
    }
}
