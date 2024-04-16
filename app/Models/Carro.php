<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carro extends Model
{
    use HasFactory;

    protected $fillable = [
        'placa', 
        'disponivel', 
        'km', 
        'modelo_id'
    ];

    public function regras() {
        return [
            'placa' => 'required|unique:carros,placa,'.$this->id.'|size:7|regex:/[A-Z]{3}[0-9][0-9A-Z][0-9]{2}/i',
            'km' => 'required|integer', 
            'disponivel' => 'required|bool',
            'modelo_id' => 'required|exists:modelos,id'
        ];
    }

    public function modelo() {
        return $this->belongsTo('App\Models\Modelo');
    }

    public function locacoes() {
        return $this->belongsTo('App\Models\Locacao');
    }
}
