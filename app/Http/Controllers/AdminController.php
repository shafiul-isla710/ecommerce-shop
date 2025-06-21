<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Brand;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\Laravel\Facades\Image;


class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }
    public function brandPage()
    {

        $brands = Brand::orderBy('id', 'DESC')->paginate(10);
        return view('admin.brand.brand-index', compact('brands'));
    }

    public function add_brand()
    {
        return view('admin.brand.add-brand');
    }

    public function store_brand(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands',
            'image' => 'mimes:jpg,png,jpeg',
        ]);

        $brand = new Brand();

        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);

        $image = $request->file('image');
        $image_extension = $image->extension();
        $file_name = Carbon::now()->timestamp . '.' . $image_extension;

        $brand->image = $file_name;
        $this->generateThumbnailImage($image, $file_name);
        $brand->save();

        return redirect()->route('admin.brand')->with('success', 'Brand added successfully');
    }

    public function generateThumbnailImage($image, $image_name)
    {

        $destinationPath = public_path('upload/brand_image');
        $img = Image::read($image->path());
        $img->cover(124, 124, 'top');

        $img->resize(124, 124, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath . '/' . $image_name);
    }
}
