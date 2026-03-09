<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Manufacturer;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
        public function index()
    {
        $products = Product::all();
        $suppliers = Supplier::all();

        $role_id = auth()->check() ? auth()->user()->role_id : null;

        return view('products.index', compact("products", "suppliers", "role_id"));
    }

    public function create()
    {
        if (!auth()->check() || auth()->user()->role_id !== 1) {
            abort(403, 'Доступ запрещен. Только для администраторов.');
        }
        $suppliers = Supplier::all();
        $manufacturers = Manufacturer::all();
        $categories = Category::all();
        return view('products.create', compact("suppliers", "manufacturers", "categories"));
    }

    public function store(Request $request)
    {
        request()->validate([
            'article'=>'required|string|unique:products',
            'name' => 'required|string',
            'price' => 'required|decimal:0,2',
            'discount' => 'required|integer',
            'quantity' => 'required|integer',
            'supplier_id' => 'required|exists:suppliers,id',
            'category_id' => 'required|exists:categories,id',
            'manufacturer_id' => 'required|exists:manufacturers,id',
            'image' => 'nullable|image:jpeg,png,jpg|max:2048',
            'description' => 'nullable|string',
        ]);

        $imageName = null;

        if ($request->hasFile("image")) {
            $file = $request->file("image");
            $imageName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/images'), $imageName);
        }

        Product::create([
            'article'=>request()->article,
            'name'=>request()->name,
            'price'=>request()->price,
            'discount'=>request()->discount,
            'quantity'=>request()->quantity,
            'supplier_id'=>request()->supplier_id,
            'category_id'=>request()->category_id,
            'manufacturer_id'=>request()->manufacturer_id,
            'image_path' => $imageName,
            'description'=>request()->description,
        ]);
        return redirect()->route('products')->with('success', 'Товар успешно добавлен');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(string $id)
    {
        if (!auth()->check() || auth()->user()->role_id !== 1) {
            abort(403, 'Доступ запрещен. Только для администраторов.');
        }
        $product = Product::find($id);
        if(!$product){
            abort(404);
        }
        $suppliers = Supplier::all();
        $manufacturers = Manufacturer::all();
        $categories = Category::all();
        return view('products.edit', compact("product", "suppliers", "manufacturers", "categories"));
    }

    public function update(Request $request, string $id)
    {
        if (!auth()->check() || auth()->user()->role_id !== 1) {
            abort(403, 'Доступ запрещен. Только для администраторов.');
        }

        $product = Product::find($id);
        if(!$product){
            abort(404);
        }

        request()->validate([
            'article' => 'required|string|unique:products,article,'.$product->id,
            'name' => 'required|string',
            'price' => 'required|decimal:0,2',
            'discount' => 'required|integer',
            'quantity' => 'required|integer',
            'supplier_id' => 'required|exists:suppliers,id',
            'category_id' => 'required|exists:categories,id',
            'manufacturer_id' => 'required|exists:manufacturers,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'nullable|string',
            'remove_image' => 'nullable|boolean',
        ]);

        $data = [
            'article' => $request->article,
            'name' => $request->name,
            'price' => $request->price,
            'discount' => $request->discount,
            'quantity' => $request->quantity,
            'supplier_id' => $request->supplier_id,
            'category_id' => $request->category_id,
            'manufacturer_id' => $request->manufacturer_id,
            'description' => $request->description,
        ];

        if ($request->has('remove_image') && $request->remove_image) {
            if ($product->image_path) {
                $imagePath = public_path('assets/images/' . $product->image_path);
                if (File::exists($imagePath)) {
                    File::delete($imagePath);
                }
            }
            $data['image_path'] = null;
        }

        if ($request->hasFile("image")) {
            // Удаляем старое изображение
            if ($product->image_path) {
                $oldImagePath = public_path('assets/images/' . $product->image_path);
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
            }

            $file = $request->file("image");
            $imageName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/images'), $imageName);
            $data['image_path'] = $imageName;
        }

        $product->update($data);

//        $imagePath = $product->image_path;
//        if(request()->hasFile("image")){
//            $imagePath = '/assets/' . request()->file("image")->store("images", "public");
//        }
//
//        Product::where('id', $product->id)->update([
//            'article'=>request()->article,
//            'name'=>request()->name,
//            'price'=>request()->price,
//            'discount'=>request()->discount,
//            'quantity'=>request()->quantity,
//            'supplier_id'=>request()->supplier_id,
//            'category_id'=>request()->category_id,
//            'manufacturer_id'=>request()->manufacturer_id,
//            'image_path'=> $imagePath,
//            'description'=>request()->description,
//        ]);
        return redirect()->route('products')->with('success', 'Товар успешно обновлен');
    }

    public function destroy(string $id)
    {
        if (!auth()->check() || auth()->user()->role_id !== 1) {
            abort(403, 'Доступ запрещен. Только для администраторов.');
        }

        $product = Product::find($id);
        if ($product && $product->image_path) {
            $imagePath = public_path('assets/images/' . $product->image_path);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        $product->delete();

        return redirect()->route('products')->with('success', 'Товар успешно удален');
    }
}
