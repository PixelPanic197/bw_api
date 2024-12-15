<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index()
    {
        return Sale::paginate(10); // Пагинация
    }
    
    public function show($id)
    {
        return Sale::findOrFail($id);
    }
}
