<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Brand;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
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

        return redirect()->route('admin.brand')->with('status', 'Brand added successfully');
    }

    public function edit_brand($id)
    {
        $brand = Brand::findOrFail($id);

        return view('admin.brand.edit-brand', compact('brand'));
    }

    // public function update_brand(Request $request)
    // {

    //     $request->validate([
    //         'name' => 'required',
    //         'slug' => 'required|unique:brands,slug,'.$request->id,
    //         'image' => 'mimes:jpg,png,jpeg',
    //     ]);

    //     $brand = Brand::find($request->id);

    //     // $brand->name = $request->name;
    //     // $brand->slug = Str::slug($request->name);
    //     $image = '';

    //     if ($request->file('image')) {
    //         if(File::exists(public_path('upload/brand_image/' . $brand->image))) {
    //             File::delete(public_path('upload/brand_image/' . $brand->image));
    //         }
    //         $image = $request->file('image');
    //         $image_extension = $image->extension();
    //         $file_name = Carbon::now()->timestamp . '.' . $image_extension;

    //         $image = $file_name;
    //         $this->generateThumbnailImage($image, $file_name);
    //     }
    //     Brand::where('id',$request->id)->update([
    //         'name' => $request->name,
    //         'slug' => Str::slug($request->name),
    //         'image' => $image
    //     ]);


    //     return redirect()->route('admin.brand')->with('status', 'Brand added successfully');
    // }



    public function update_brand(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,' . $request->id,
            'image' => 'nullable|mimes:jpg,png,jpeg',
        ]);

        $brand = Brand::find($request->id);

        $image = $brand->image;

        if ($request->file('image')) {
            if (File::exists(public_path('upload/brand_image/' . $brand->image))) {
                File::delete(public_path('upload/brand_image/' . $brand->image));
            }

            $uploaded = $request->file('image');
            $file_name = Carbon::now()->timestamp . '.' . $uploaded->extension();
            $uploaded->move(public_path('upload/brand_image/'), $file_name);
            $image = $file_name;

            // Optional: Call your thumbnail function if needed
            // $this->generateThumbnailImage($uploaded, $file_name);
        }

        $brand->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'image' => $image
        ]);

        return redirect()->route('admin.brand')->with('status', 'Brand updated successfully');
    }

    public function delete_brand($id){
        $brand  = Brand::findOrFail($id);

        if(File::exists(public_path('upload/brand_image/'.$brand->image))){
            File::delete(public_path('upload/brand_image/'.$brand->image));
        }

        $brand->delete();
        return redirect()->route('admin.brand')->with('status', 'Brand deleted successfully');
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
