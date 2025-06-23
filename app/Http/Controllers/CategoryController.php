<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;


class CategoryController extends Controller
{

    public function CategoryPage()
    {
        $categories = Category::orderBy('id', 'desc')->paginate(10);
        return view('admin.category.category-index', compact('categories'));
    }

    public function add_category(Request $request){

        return view('admin.category.add-category');
    }

    public function store_category(Request $request){

        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories',
            'image' => 'mimes:jpg,png,jpeg',
        ]);

        $categories = new Category();

        $categories->name = $request->name;
        $categories->slug = Str::slug($request->name);

        $image = $request->file('image');
        $image_extension = $image->extension();
        $file_name = Carbon::now()->timestamp . '.' . $image_extension;

        $categories->image = $file_name;
        $this->generateThumbnailImage($image, $file_name);
        $categories->save();

        return redirect()->route('admin.category')->with('status', 'Category added successfully');

    }

    public function edit_category($id)
    {
        $category = Category::findOrFail($id);

        return view('Admin.category.edit-category', compact('category'));
    }

     public function update_category(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,' . $request->id,
            'image' => 'nullable|mimes:jpg,png,jpeg',
        ]);

        $category = category::find($request->id);

        $image = $category->image;

        if ($request->file('image')) {
            if (File::exists(public_path('upload/category_images/' . $category->image))) {
                File::delete(public_path('upload/category_images/' . $category->image));
            }

            $uploaded = $request->file('image');
            $file_name = Carbon::now()->timestamp . '.' . $uploaded->extension();
            $uploaded->move(public_path('upload/category_images/'), $file_name);
            $image = $file_name;

        }

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'image' => $image
        ]);

        return redirect()->route('admin.category')->with('status', 'Category updated successfully');
    }

    public function delete_category($id){
        $category = Category::findOrFail($id);
        if(File::exists(public_path('upload/category_images/'.$category->image))){
            File::delete(public_path('upload/category_images/' . $category->image));
        }
        $category->delete();
        return redirect()->route('admin.category')->with('status', 'Category deleted successfully');
    }



     public function generateThumbnailImage($image, $image_name)
    {

        $destinationPath = public_path('upload/category_images');
        $img = Image::read($image->path());
        $img->cover(124, 124, 'top');

        $img->resize(124, 124, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath . '/' . $image_name);
    }
}
