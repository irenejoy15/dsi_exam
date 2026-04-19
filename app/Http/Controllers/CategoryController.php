<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        if($search != null){
            $categories = Category::where('name', 'like', '%' . $search . '%')->paginate(5);
        } else {
            $categories = Category::paginate(5);
        }
        return view('categories.index', compact('categories', 'search'));
    }
    
    public function store(CategoryStoreRequest $request)
    {   
        $name = $request->input('name');
        $description = $request->input('description');
        $data_create = [
            'name' => $name,
            'description' => $description,
        ];
        Category::create($data_create);
        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    public function update(CategoryUpdateRequest $request)
    {
        $id = $request->input('update_id');
        $name = $request->input('update_name');
        $description = $request->input('update_description');
        $data_update = [
            'name' => $name,
            'description' => $description,
        ];
        Category::where('id', $id)->update($data_update);
        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Request $request)
    {
        $id = $request->input('delete_id');
        Category::destroy($id);
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }
}
