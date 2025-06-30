<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Manager\ImageUploadManager;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;

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

        $product = $request->except('image');

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

            $product['image'] = ImageUploadManager::imageUpload($name2,  $width, $height, $path,  $file);
            ImageUploadManager::imageUpload($name2,  $width_thump, $height_thump, $path_thump,  $file);
        }

        (new Product())->createProduct($product);

        return redirect()->route('admin.products')->with('success', 'Product added successfully');
    }

    public function product_edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::select('id', 'name')->orderBy('name')->get();
        $brands = Brand::select('id', 'name')->orderBy('name')->get();

        return view('Admin.product.edit-product', compact('product', 'categories', 'brands'));
    }

    public function product_update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:products,slug,' . $request->id,
        ]);

        $product = Product::findOrFail($request->id);
        $product_image = $product->image;

        if ($request->hasFile('image')) {

            if (File::exists(public_path('upload/products_images/' . $product->image))) {
                File::delete(public_path('upload/products_images/' . $product->image));
            }
            if (File::exists(public_path('upload/products_images/thumbnails/' . $product->image))) {
                File::delete(public_path('upload/products_images/thumbnails/' . $product->image));
            }


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

            $product['image'] = ImageUploadManager::imageUpload($name2,  $width, $height, $path,  $file);
            ImageUploadManager::imageUpload($name2,  $width_thump, $height_thump, $path_thump,  $file);

            // $updated_image = $request->file('image');
            // $imageName = Carbon::now()->timestamp . '.' . $updated_image->extension();
            // $updated_image->move(public_path('upload/products_images/'), $imageName);
            // $image = $imageName;
        }
        

        $product->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'short_description' => $request->short_description,
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            'SKU' => $request->SKU,
            'stock_status' => $request->stock_status,
            'featured' => $request->featured,
            'quantity' => $request->quantity,
            'image' => $name2,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
        ]);

        return redirect()->route('admin.products')->with('status', 'Product updated successfully');
    }

    public function delete_product(Request $request)
    {
        $product = Product::findOrFail($request->id);

        if (File::exists(public_path('upload/products_images/' . $product->image))) {
            File::delete(public_path('upload/products_images/' . $product->image));
        }
        $product->delete();
        return redirect()->route('admin.products')->with('success', 'Product deleted successfully');
    }
}
