<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class mean_db extends Model
{
    // 1. Especificar el nombre real de la tabla
    protected $table = 'mean_data';

    // 2. Desactivar timestamps porque tu tabla no tiene created_at/updated_at
    public $timestamps = false;

    // 3. Indicar que no hay una clave primaria 'id' única o autoincremental
    protected $primaryKey = null;
    public $incrementing = false;

    // 4. (Opcional) Permitir que Laravel lea todas las columnas
    protected $guarded = [];
}