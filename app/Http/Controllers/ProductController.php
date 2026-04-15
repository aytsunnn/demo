<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Manufacturer;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();
        $sellers = Seller::all();
        $role_id = auth()->check() ? auth()->user()->role_id : null;

        return view("products.index", compact('products', 'sellers', 'role_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->check() || auth()->user()->role_id !== 1){
            abort(403, 'Доступно только администратору');
        }

        $sellers = Seller::all();
        $manufacturers = Manufacturer::all();
        $categories = Category::all();

        return view("products.create", compact('sellers', 'manufacturers', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
           'article'=>'required|unique:products',
           'name'=>'required',
            'price'=>'required|decimal:0,2',
            'seller_id'=>'required',
            'manufacturer_id'=>'required',
            'category_id'=>'required',
            'discount'=>'required|min:0|max:100',
            'quantity'=>'required|min:0',
            'description'=>'required',
            'image'=>'image|mimes:jpg,jpeg,png|max:2048|nullable',
        ]);

        $imageName = null;

        if ($request->hasFile('image')){
            $file = $request->file('image');
            $imageName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/images/'), $imageName);
        }

        Product::create([
            'article'=>request()->article,
            'name'=>request()->name,
            'price'=>request()->price,
            'seller_id'=>request()->seller_id,
            'manufacturer_id'=>request()->manufacturer_id,
            'category_id'=>request()->category_id,
            'discount'=>request()->discount,
            'quantity'=>request()->quantity,
            'description'=>request()->description,
            'image_path'=>$imageName,
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
    public function edit($id)
    {
        if (!auth()->check() || auth()->user()->role_id !== 1){
            abort(403, 'Доступно только администратору');
        }

        $product = Product::findOrFail($id);

        $sellers = Seller::all();
        $manufacturers = Manufacturer::all();
        $categories = Category::all();

        return view("products.edit", compact('product', 'sellers', 'manufacturers', 'categories'));
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
        $product = Product::findOrFail($id);

        request()->validate([
           'article'=>'required|unique:products,article,'.$product->id,
           'name'=>'required',
            'price'=>'required|decimal:0,2',
            'seller_id'=>'required',
            'manufacturer_id'=>'required',
            'category_id'=>'required',
            'discount'=>'required|min:0|max:100',
            'quantity'=>'required|min:0',
            'description'=>'required',
            'image'=>'image:jpg,jpeg,gif,png|nullable|max:2048',
            'remove_image'=>'nullable|boolean',
        ]);

        $data = [
          'article'=>$request->article,
          'name'=>$request->name,
          'price'=>$request->price,
            'seller_id'=>$request->seller_id,
            'manufacturer_id'=>$request->manufacturer_id,
            'category_id'=>$request->category_id,
            'discount'=>$request->discount,
            'quantity'=>$request->quantity,
            'description'=>$request->description,
        ];

        if ($request->remove_image == '1'){
            if ($product->image_path){
                $imagePath = public_path('assets/images/'.$product->image_path);
                if (File::exists($imagePath)){
                    File::delete($imagePath);
                }
            }
            $data['image_path']=null;
        }

        if ($request->hasFile('image')){
            if ($product->image_path){
                $imagePath = public_path('assets/images/'.$product->image_path);
                if (File::exists($imagePath)){
                    File::delete($imagePath);
                }
            }
            $file = $request->file('image');
            $imageName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/images/'), $imageName);
            $data['image_path'] = $imageName;
        }

        $product->update($data);

        return redirect()->route('products')->with('success', 'Товар успешно обновлен');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!auth()->check() || auth()->user()->role_id !== 1){
            abort(403, 'Доступно только администратору');
        }

        $product = Product::findOrFail($id);

        if ($product->details()->exists()) {
            return redirect()->route('products')->with('warning', 'Нельзя удалить товар, который есть в заказах');
        }

        if ($product->image_path){
            $imagePath = public_path('assets/images/'.$product->image_path);
            if (File::exists($imagePath)){
                File::delete($imagePath);
            }
            $product->image_path = null;
        }

        $product->delete();

        return redirect()->route('products')->with('success', 'Товар успешно удален');
    }
}
