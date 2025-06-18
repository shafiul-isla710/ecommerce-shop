<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(){
        return view('admin.index');
    }
    public function brandPage(){

        $brands = Brand::orderBy('id','DESC')->paginate(10);
        return view('admin.brand',compact('brands'));
        
    }
}
