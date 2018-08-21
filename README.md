# Easy way to make datatable(s) on Laravel
This is a simple method to make datatables on Laravel with a fast call and configuration.

## No Package
This is no package. In the future it may be.
But now is only a simple way to make datatables. We only use one **trait**, **class** and two **views**.

I make this because I tried different packages for develop datatables with Laravel, but no one works on useful way.
With no one we save time implementing the datatables. All packages are as useful and fast as create the table and include and configure DataTable jQuery.

But with this controller and views, we improve the way to include datatables on Laravel.

## Features
This way have some useful things:

* The first element contain a link to edit or show this item (p.e. if you show users, first element link with user profile)
* The first element link is by default *controller*.**edit**, but is posible to change it
* With other elements, if the element is an email, the system convert to email link
* Not need to configurate jQuery (but is possible, because the script is one of two views)
* Is possible to create multiple structure of datatable. Only need to copy datatable, modify it and call it with **custom version**

## Usage
* We assume you have jquery included in your HTML

1. Include jquery and css Datatable on your header/footer
```
<link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
<script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
```

2. Include Trait (example how do it down, into "Usage")
2.1 **Trait**
```php
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
     * @param   boolean     $search         [true = se muestran inputs de busqueda en cada columna (pierde traducción)]
     * @return vista HTML
     */
    public function script($dt_id, $search = false)
    {
        $view = \View::make('datatable.script', ['datatable_id' => $dt_id, 'search' => $search]);
        $contents = $view->render();

        return  $contents;
    }
}

```

2.1 **Class**

```php
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


```

3. (Only for **custom datatable version**) We create a new folder into views folder. Call it *datatable*. Inside we put the datatable views files, **datatable.blade.php** and **script.blade.php**
3.1 **datatable.blade.php**
```php
<table id="{{ $datatable_id }}" class="table table-striped table-hover" cellspacing="0">
    <thead>
        <tr>
            @if ($titles)
                @for ($i = 0; $i < count($titles); $i++)
                    <th>{!! $titles[$i] !!}</th>
                @endfor
            @else
                @for ($i = 0; $i < count($array_values); $i++)
                    <th>{!! $array_values[$i] !!}</th>
                @endfor
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach ($values as $value)
        <tr>
            @for ($i = 0; $i < count($array_values); $i++)
                @if ($i == 0)
                    <td>
                        @if ($route)
                        <a href="{{ route($link . '.' . $route, $value) }}" class="text-success">
                            <strong>{!! $value->getAttribute($array_values[$i]) !!}</strong>
                        </a>
                        @else
                        <span class="text-info">
                            <strong>{!! $value->getAttribute($array_values[$i]) !!}</strong>
                        </span>
                        @endif
                    </td>
                @else
                    @if (filter_var($value->getAttribute($array_values[$i]), FILTER_VALIDATE_EMAIL))
                        <td>
                            <a href="mailto:{!! $value->getAttribute($array_values[$i]) !!}" class="text-info">
                                {!! $value->getAttribute($array_values[$i]) !!}
                            </a>
                        </td>
                    @else
                        <td>{!! $value->getAttribute($array_values[$i]) !!}</td>
                    @endif
                @endif
            @endfor
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            @if ($titles)
                @for ($i = 0; $i < count($titles); $i++)
                    <th>{!! $titles[$i] !!}</th>
                @endfor
            @else
                @for ($i = 0; $i < count($array_values); $i++)
                    <th>{!! $array_values[$i] !!}</th>
                @endfor
            @endif
        </tr>
    </tfoot>
</table>
```
3.2 **script.blade.php**
```php
<script type="text/javascript">
    $(function() {
        window.table_id = '#' + '{{ $datatable_id }}';

        window.table = $(table_id).DataTable({
        	@if (!$search)
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.10.10/i18n/Spanish.json"
            }
            @endif
        });

        @if ($search)
        window.table_h = $(table_id + ' thead th');
        window.table_f = $(table_id + ' tfoot th');
        @endif
    });
</script>
```

## Usage
The way to use is very simple: On your controller, into method you want, you store into variable the data you want from database. After you only need to call the 2 views.

We have to complete the datatable.

Default version (loadDatatable):
```php
$datatable = $dt->loadDatatable($id_datatable, $model, $controller, $template = null, $method = 'edit', $search = false);
```

**Note:** template, method, search and nameCustomTemplate are optionals.

Default version (loadDatatable):
```php
$datatable = $dt->loadcustomDatatable('datatable_users', $users, $array_datas, $array_titles, 'users', 'admin.users.datatable.datatable', $method = null, $search = false);
```

**Note:** method, search and nameCustomTemplate are optionals.

**EXAMPLE** If I want to show all users from `UsersController@index`, do this :
Into controller
```php
<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;
use App\Traits\DatatableGenerator;

class TestController extends Controller
{
    public function index()
    {
        $users = User::all();

        $dt = new DatatableGenerator();

        $array_datas = ['name', 'email'];
        $array_titles = ['Name', 'Email'];

        // Load custom version for datatable HTML structure
        $datatable = $dt->customDatatable('datatable_users', $users, $array_datas, $array_titles, 'users', 'admin.users.datatable.datatable');

        return view('admin.users.index', [
                                            'datatable' => $datatable['datatable'],
                                            'script'    => $datatable['script']
                                        ]);
    }

    public function index_version_load()
    {
        $users = User::all();

        $dt = new DatatableGenerator();

        $array_datas = ['name', 'email'];
        $array_titles = ['Name', 'Email'];

        // Load default version for datatable HTML structure
        $datatable = $dt->loadDatatable($id_datatable, $model, $controller, $template = null, $method = 'edit', $search = false);

        return view('admin.users.index', [
                                            'datatable' => $datatable['datatable'],
                                            'script'    => $datatable['script']
                                        ]);
    }
}
```

Into view
```php
@extends('app')

@section('content')
	<div class="col-xs-12 col-sm-10">
		{!! $datatable !!}
	</div>
@endsection

@section('scripts')
	{!! $script !!}
@endsection
```

### Credits
We hope it will be useful for you!
[freshware.es](http://freshware.es)