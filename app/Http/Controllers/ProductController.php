<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\Laravel\Facades\Image;
use App\Manager\ImageUploadManager;

class ProductController extends Controller
{
    public function products()
    {

        $products = Product::with('category', 'brand')->orderBy('created_at', 'desc')->paginate(10);
       
        return view('Admin.product.product-index', ['products' => $products]);
    }

    public function add_product()
    {

        $categories = Category::select('id', 'name')->orderBy('name')->get();
        $brands = Brand::select('id', 'name')->orderBy('name')->get();
        return view('Admin.product.add-product', compact('categories', 'brands'));
    }


    public function store_product(Request $request)
    
    {

        $category = $request->except('image');

        // $category['slug'] = Str::slug($request->input('slug'));


        if ($request->hasFile('image')) {

            $file = $request->image;

            $width = 688;

            $height = 540;

            $width_thump = 104;
            $height_thump = 104;
            $name = Str::slug($request->slug);
            $name1 = $name . '.png';
            $name2 = hexdec(uniqid()) . '-' . $name1;

            $path = 'upload/products_images/';
            $path_thump = 'upload/products_images/thumbnails/';

            $category['image'] = ImageUploadManager::imageUpload($name2,  $width, $height, $path,  $file);
            ImageUploadManager::imageUpload($name2,  $width_thump, $height_thump, $path_thump,  $file);
        }

        (new Product())->createCategory($category);

        return redirect()->route('admin.products')->with('success', 'Product added successfully');
    }
}
