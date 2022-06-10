<?php

namespace App\Http\Controllers\Front;

use App\Models\Admin\Banner;
use Illuminate\Http\Request;
use App\Models\Admin\Product;
use App\Models\Admin\Category;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{

    public function index(){
        $categories = Category::select('id','title','photo','slug')->active()->limit(3)->get();
        $banners = Banner::select('id','title','photo')->active()->get();
        $products = Product::get();
        //return $categories;
        return view('front.index', compact('categories','banners','products'));
    }




    public function category($slug, Request $request){


        // if($request->sort){
        //     list($sortby, $order) = preg_split('/(?=[A-Z])/',$request->sort);
        // }

    	$category = Category::where('slug',$slug)->first();

    	if(!$category){
    		return redirect()->back();
    	}

        if($request->sort){
            if($request->sort == 'priceAsc'){
                $products = Product::where('category_id',$category->id)->orderBy('price', 'ASC')->paginate();
            }elseif($request->sort == 'priceDesc'){
                $products = Product::where('category_id',$category->id)->orderBy('price', 'DESC')->paginate();
            }elseif($request->sort == 'titleAsc'){
                $products = Product::where('category_id',$category->id)->orderBy('name', 'ASC')->paginate();
            }else{
                $products = Product::where('category_id',$category->id)->orderBy('name', 'DESC')->paginate();
            }
        }else{
            $products = Product::where('category_id',$category->id)->orderBy('name', 'DESC')->paginate();
        }

    	$route = 'category';
    	return view('front.category', compact('category', 'route', 'products'));
    }



    public function product($slug){

    	$product = Product::with(['relateds' => function($q){
            return $q->limit(4)->active();
        }])->where('slug',$slug)->first();

    	if(!$product){
    		return redirect()->back();
    	}


    	return view('front.product', compact('product'));
    }
}
