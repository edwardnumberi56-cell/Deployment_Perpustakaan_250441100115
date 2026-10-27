<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index()
    {
        return view('buku.index'); // Hanya merender file view blade untuk halaman web
    }
}