<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Brand;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Admin\Product;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Admin\Category;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::where('translation_lang',get_default_lang())->selection()->get();
        return view('dashboard.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $brands = Brand::active()->get();
        return view('dashboard.products.create', compact('brands'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //return $request;
        try{
            // Convert to collect
            $products = collect($request->product);

            // filter values by current default language
            $filter = $products->filter(function($value,$key){
                return $value['translation_lang'] == get_default_lang();
            });

            $default_product = array_values($filter->all())[0];

            //return $default_product['photo'] ;

            //upload image
            $filePath = [];
            if($default_product['photo']){
                foreach($default_product['photo'] as $photo){
                    $filePath[] = uploadImage('products', $photo);
                }
            }


            DB::beginTransaction();

            $default_product_id = Product::insertGetId([
                'translation_lang'  => $default_product['translation_lang'],
                'translation_of'    => 0,
                'summary'           => $default_product['summary'],
                'description'       => $default_product['description'],
                'name'              => $default_product['name'],
                'brand_id'          => $default_product['brand_id'],
                'category_id'       => $default_product['category_id'],
                'price'             => $default_product['price'],
                'offer_price'       => $default_product['offer_price'],
                'stock'             => $default_product['stock'],
                'status'            => $default_product['status'] ?? 0,
                'slug'              => Str::slug($default_product['name']),
                'photo'             => json_encode($filePath)
            ]);

            // filter values by other language
            $other_products = $products->filter(function ($value, $key) {
                return $value['translation_lang'] != get_default_lang();
            });

            if (isset($other_products) && $other_products->count()) {

                $products_arr = [];
                foreach ($other_products as $product) {
                    $arr_photo = [];
                    if($product['photo']){
                        foreach($product['photo'] as $photo){
                            $arr_photo[] = uploadImage('products', $photo);
                        }
                    }
                    $products_arr[] = [
                        'translation_lang'  => $product['translation_lang'],
                        'translation_of'    => $default_product_id,
                        'summary'           => $product['summary'],
                        'description'       => $product['description'],
                        'name'              => $product['name'],
                        'brand_id'          => $product['brand_id'],
                        'category_id'       => $product['category_id'],
                        'price'             => $product['price'],
                        'offer_price'       => $product['offer_price'],
                        'stock'             => $product['stock'],
                        'status'            => $product['status'] ?? 0,
                        'slug'              => Str::slug($product['name']),
                        'photo'             => json_encode($arr_photo)
                    ];
                }

                Product::insert($products_arr);
            }

            DB::commit();

            return redirect()->route('dashboard.products.index')->with(['success' => 'تم الحفظ بنجاح']);
        }catch(\Exception $ex){
            DB::rollback();
            return $ex;
            return redirect()->route('dashboard.products.index')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }
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
        $product = Product::with('childrens')->find($id);
        if(!$product){
            return redirect()->route('dashboard.products.index')->with(['error' => 'this product not exists']);
        }

        return view('dashboard.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, ProductRequest $request)
    {
        //return $request;
        try{
            $product = Product::find($id);

            if(!$product){
                return redirect()->route('dashboard.products.index')->with(['error' => 'product not exists']);
            }

            $collect = collect($request->product)[0];

            $photo_path = $product->photo;

            if(isset($collect['photo'])){
                if($product->photo != ''){
                    if(is_file(base_path('public/assets/admin/' . $product->photo))){
                        unlink(base_path('public/assets/admin/'.$photo_path));
                    }
                }
            $photo_path = uploadImage('products',$collect['photo']);
            }


            $product->update([
                'summary' => $collect['summary'],
                'brand_id' => $collect['brand_id'],
                'category_id' => $collect['category_id'],
                'price' => $collect['price'],
                'offer_price' => $collect['offer_price'],
                'stock' => $collect['stock'],
                'name'         => $collect['name'],
                'description'   => $collect['description'],
                'status'        => $collect['status'] ?? 0,
                'slug'          => Str::slug($collect['name']),
                'photo'         => $photo_path
            ]);

            return redirect()->route('dashboard.products.index')->with(['success' => 'تم التحديث بنجاح']);

        }catch(\Exception $ex){
            return $ex;
            return redirect()->route('dashboard.products.index')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        if(!$product){
            return redirect()->route('dashboard.products.index')->with(['error' => 'this product not exists']);
        }

        if($product->photo != ''){
            if(is_file(base_path('public/assets/admin/' . $product->photo))){
                unlink(base_path('public/assets/admin/'.$product->photo));
            }
         }


        $product->delete();
        return redirect()->route('dashboard.products.index')->with(['success' => 'تم الحذف بنجاح']);
    }
}
