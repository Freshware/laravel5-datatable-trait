<?php

namespace App\Traits;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

trait DatatableTrait
{
    /**
     * Para mostrar datatables.
     *
     * @param   string      $template       [cambiamos la plantilla por defecto por otra a nuestro gusto]
     * @param   string      $dt_id          [el id de la tabla que se convertira en datatable]
     * @param   Collection  $values         [representa la colección de valores que se han de incorporar a la tabla]
     * @param   array       $array_values   [array que contiene los atributos/columnas a mostrar]
     * @param   string      $route          [ruta del controlador al que saltar]
     * @param   string      $link           [dirección a la que saltar en el primer parámetro, por defecto 'edit']
     * @param   boolean     $search         [true = se muestran inputs de busqueda en cada columna (pierde traducción)]
     * @param   array       $titles         [array que contiene los títulos de la tabla. Si es null se mostrará los atributos/columnas]
     * @return vista HTML
     */
    public function datatable($dt_id, $values, $array_values, $route = NULL, $link = 'edit', $search = false, $template = null, $titles = null)
    {
        if (!$template)
        {
            $template = 'datatable.datatable';
        }

        $view = \View::make($template, [
                                            'values'        => $values,
                                            'datatable_id'  => $dt_id,
                                            'array_values'  => $array_values,
                                            'route'         => $route,
                                            'link'          => $link,
                                            'search'        => $search,
                                            'template'      => $template,
                                            'titles'        => $titles
                                        ]);
        $contents = $view->render();

        return  $contents;
    }

    /**
     * Para mostrar el jQuery que carga los datatables.
     *
     * @param   string      $dt_id          [el id de la tabla que se convertira en datatable]
     * @param   string      $search         [indica si se añade el buscador o no]
     * @param   string      $columnSort     [la columna desde la que se quiere ordenar (la primera es 0)]
     * @param   string      $sorting        [sentido del orden (por defecto descendente)]
     * @param   boolean     $search         [true = se muestran inputs de busqueda en cada columna (pierde traducción)]
     * @param   string      $template       [cambiamos el script por defecto por otra a nuestro gusto]
     * @return vista HTML
     */
    public function script($dt_id, $search = false, $columnSort = null, $sorting = 'desc', $template)
    {
        $view = \View::make($template, [
                                                    'datatable_id'  => $dt_id,
                                                    'search'        => $search,
                                                    'columnSort'    => $columnSort,
                                                    'sorting'       => $sorting
                                                ]);
        $contents = $view->render();

        return  $contents;
    }
}
