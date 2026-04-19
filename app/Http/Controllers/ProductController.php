<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Carbon\Carbon;
use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductUpdateRequest;

use Illuminate\Support\Facades\Storage;
class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $categories = Category::all();
        if($search != null){
            $products = Product::where('name', 'like', '%' . $search . '%')->paginate(3);
        } else {
            $products = Product::paginate(3);
        }
        return view('products.index', compact('products', 'search','categories'));
    }

    public function store(ProductCreateRequest $request)
    {
        $name = $request->input('name');
        $description = $request->input('description');
        $price = $request->input('price');
        $category_id = $request->input('category_id');
        $stock = $request->input('stock');
        $is_active = $request->input('is_active');

        $image = $request->file('image');
        
        $product_name = 'P'.time().'.'.$image->extension();

        Storage::disk('public')->put('/uploads/products/'.$product_name, file_get_contents($image));

        $product_image_post = $product_name;
        $data = [
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'category_id' => $category_id,
            'stock' => $stock,
            'is_active' => $is_active,
            'image' => $product_image_post,
        ];
        Product::create($data);
        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function update(Request $request)
    {
        $id = $request->input('update_id');
        $name = $request->input('update_name');
        $description = $request->input('update_description');
        $price = $request->input('update_price');
        $category_id = $request->input('update_category_id');
        $stock = $request->input('update_stock');
        $is_active = $request->input('update_is_active');
        $image = $request->file('update_image');
        
        if (!empty($image)):
            // UNLINK OLD IMAGE
            $old_image = Product::where('id', $id)->first()->image;
            if (file_exists(public_path('storage/uploads/products/'.$old_image))) {
                unlink(public_path('storage/uploads/products/'.$old_image));
            }
            $product_name = 'P'.time().'.'.$image->extension();
            Storage::disk('public')->put('/uploads/products/'.$product_name, file_get_contents($image));
            $data_update = [
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'category_id' => $category_id,
                'stock' => $stock,
                'is_active' => $is_active,
                'image' => $product_name,
            ];
        else:
            $data_update = [
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'category_id' => $category_id,
                'stock' => $stock,
                'is_active' => $is_active,
            ];
        endif;
        
        Product::where('id', $id)->update($data_update);
        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Request $request)
    {
        $id = $request->input('delete_id');
        $product = Product::where('id', $id)->first();
        if (file_exists(public_path('storage/uploads/products/'.$product->image))) {
            unlink(public_path('storage/uploads/products/'.$product->image));
        }
        Product::destroy($id);
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
