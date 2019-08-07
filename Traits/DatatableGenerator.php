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

    public function loadDatatable($id_datatable, $model, $controller, $template = 'datatable.datatable', $method = 'edit', $search = false, $columnSort = null, $sorting = 'desc', $template_script = 'datatable.script')
    {
        $data = $titles = $model->getFillable();

        $model = $model->all();

        $dt = new DatatableGenerator();        
        
        $datatable = $this->datatable(
                                    $id_datatable, $model, $data, $method, $controller, $search, $template, $titles
                                );
            
        $script = $dt->script($id_datatable, $search, $columnSort, $sorting, $template_script);

        return ['datatable' => $datatable, 'script' => $script];
    }

    public function customDatatable($id_datatable, $model, $data, $titles, $controller = null, $template = 'datatable.datatable', $method = null, $search = false, $columnSort = null, $sorting = 'desc', $template_script = 'datatable.script')
    {
        $method = ($controller && (! $method)) ? 'edit' : null;

        $dt = new DatatableGenerator();        
        
        $datatable = $dt->datatable(
                                    $id_datatable, $model, $data, $method, $controller, $search, $template, $titles
                                );
            
        $script = $dt->script($id_datatable, $search, $columnSort, $sorting, $template_script);

        return ['datatable' => $datatable, 'script' => $script];
    }
}
