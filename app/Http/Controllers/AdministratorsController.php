<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\SuperAdminMiddleware;
use App\Models\User;

class AdministratorsController extends Controller
{
    public function __construct()
    {
        $this->middleware(AdminMiddleware::class);
        $this->middleware(SuperAdminMiddleware::class)->only('indexSuper');
    }

    public function index()
    {
        //$users = User::where('role', '<>', 'admin')->orderBy('name')->get();
        $users = User::where('id', '<>', 1)->orderBy('name')->get();
        dd($users);
    }

    public function indexSuper()
    {
        $users = User::orderBy('name')->get();
        dd($users);
    }
}
