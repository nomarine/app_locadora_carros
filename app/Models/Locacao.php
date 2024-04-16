<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locacao extends Model
{
    use HasFactory;

    protected $table = 'locacoes';

    protected $fillable = [
        'data_inicio_periodo',
        'data_final_previsto_periodo',
        'data_final_realizado_periodo',
        'valor_diaria',
        'km_inicial',
        'km_final',
        'cliente_id',
        'carro_id'
    ];

    public function regras() {
        return [
            'data_inicio_periodo' => 'required',
            'data_final_previsto_periodo' => '',
            'data_final_realizado_periodo' => '',
            'valor_diaria' => 'required|numeric|max:99999999',
            'km_inicial' => 'required|integer',
            'km_final' => 'integer',
            'cliente_id' => 'required|exists:clientes,id',
            'carro_id' => 'required|exists:carros,id'
        ];
    }

    public function cliente() {
        return $this->belongsTo('App\Models\Cliente');
    }

    public function carro() {
        return $this->belongsTo('App\Models\Carro');
    }
}
