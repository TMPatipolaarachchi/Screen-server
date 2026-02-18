<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class CategoryController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function index()
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        return view('categories.create');
    }

    public function store(Request $request)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            $code = Category::generateCode();
            Category::create(array_merge($request->all(), ['code' => $code, 'status' => 'active']));
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '23000') { // Unique constraint violation
                return redirect()->back()->withErrors(['name' => 'The name has already been taken.'])->withInput();
            }

            throw $e; // Re-throw other exceptions
        }

        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    public function show(Category $category)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        return view('categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'code' => 'required|string|max:50|unique:categories,code,' . $category->id,
            'status' => 'required|in:active,inactive',
        ]);

        try {
            $category->update($request->all());
            return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update category. The category name or code may already exist.')->withInput();
        }
    }

    public function toggleStatus(Category $category)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        $newStatus = $category->status === 'active' ? 'inactive' : 'active';
        $category->update(['status' => $newStatus]);

        $message = $newStatus === 'active' 
            ? 'Category activated successfully.' 
            : 'Category deactivated successfully.';

        return redirect()->route('categories.index')->with('success', $message);
    }
}
