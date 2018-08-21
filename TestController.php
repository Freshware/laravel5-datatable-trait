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
        $datatable = $dt->customDatatable('datatable_users', $users, $array_datas, $array_titles, 'users', 'admin.users.datatable.datatable', null);

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
