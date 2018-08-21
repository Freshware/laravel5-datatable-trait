<?php

namespace App\Traits;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Traits\DatatableTrait;

class DatatableGenerator
{
    use DatatableTrait;

    public function __construct()
    {
        return $this;
    }

    public function loadDatatable($id_datatable, $model, $controller, $template = null, $method = 'edit', $search = false)
    {
        $data = $titles = $model->getFillable();

        $model = $model->all();

        $dt = new DatatableGenerator();        
        
        $datatable = $this->datatable(
                                    $id_datatable, $model, $data, $method, $controller, $search, $template, $titles
                                );
            
        $script = $dt->script($id_datatable, $search);

        return ['datatable' => $datatable, 'script' => $script];
    }

    public function customDatatable($id_datatable, $model, $data, $titles, $controller = null, $template = null, $method = null, $search = false)
    {
        $method = ($controller && (! $method)) ? 'edit' : null;

        $dt = new DatatableGenerator();        
        
        $datatable = $dt->datatable(
                                    $id_datatable, $model, $data, $method, $controller, $search, $template, $titles
                                );
            
        $script = $dt->script($id_datatable, $search);

        return ['datatable' => $datatable, 'script' => $script];
    }
}
