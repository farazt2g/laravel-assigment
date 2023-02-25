<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function csrf_token()
    {
        return csrf_token(); 
    }

    public function index()
    {
        $key = 'products';
    $minutes = 60;

    $products = Cache::remember($key, $minutes, function () {
        return Product::all();
    });

    return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request data
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric|min:0'
    ]);

    // Create a new product
    $product = new Product();
    $product->name = $validatedData['name'];
    $product->description = $validatedData['description'];
    $product->price = $validatedData['price'];
    $product->save();

    // Return the newly created product as a JSON response
    return response()->json($product, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $key = 'product_' . $id;
    $minutes = 60;

    $product = Cache::remember($key, $minutes, function () use ($id) {
        return Product::find($id);
    });

    if (!$product) {
        return response()->json(['error' => 'Product not found'], 404);
    }

    return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         // Find the product by ID
    $product = Product::find($id);

    // If the product doesn't exist, return a 404 error
    if (!$product) {
        return response()->json(['message' => 'Product not found'], 404);
    }

    // Validate the request data
    $validatedData = $request->validate([
        'name' => 'string|max:255',
        'description' => 'string',
        'price' => 'numeric|min:0'
    ]);

    // Update the product with the validated data
    $product->fill($validatedData);
    $product->save();

    // Return the updated product as a JSON response
    return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Find the product by ID
    $product = Product::find($id);

    // If the product doesn't exist, return a 404 error
    if (!$product) {
        return response()->json(['message' => 'Product not found'], 404);
    }

    // Delete the product from the database
    $product->delete();

    // Return a success message as a JSON response
    return response()->json(['message' => 'Product deleted']);
    }
}
