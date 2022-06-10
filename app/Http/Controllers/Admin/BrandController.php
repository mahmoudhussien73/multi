<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BrandRequest;
use App\Models\Admin\Brand;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brands = Brand::selection()->get();
        return view('dashboard.brands.index', compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.brands.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BrandRequest $request)
    {
        try{
            $filePath = uploadImage('brands', $request->photo);

            Brand::insert([
                'name' => $request->name,
                'slug'  => Str::slug($request->name),
                'status'    => $request->status ?? 0,
                'photo' => $filePath
            ]);

            return redirect()->route('dashboard.brands.index')->with(['success' => 'تمت الاضافة بنجاح']);

        }catch(\Exception $ex){
            return $ex;
            return redirect()->route('dashboard.brands.index')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
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
        $brand = Brand::find($id);
        if(!$brand){
            return redirect()->route('dashboard.brands.index')->with(['error' => 'this brand not exists']);
        }


        return view('dashboard.brands.edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BrandRequest $request, $id)
    {
        try{
            $brand = Brand::find($id);
            if(!$brand){
                return redirect()->route('dashboard.brands.index')->with(['error' => 'this brand not exists']);
            }

            $photo_path = $brand->photo;

            if(isset($request->photo)){
                if($brand->photo != ''){
                    if(is_file(base_path('public/assets/admin/' . $brand->photo))){
                        unlink(base_path('public/assets/admin/'.$photo_path));
                    }
                }
                $photo_path = uploadImage('brands',$request->photo);
            }

            $brand->update([
                'name'  => $request->name,
                'slug'  => Str::slug($request->name),
                'status'    => $request->status ?? 0,
                'photo' => $photo_path
            ]);

            return redirect()->route('dashboard.brands.index')->with(['success' => 'تمت الاضافة بنجاح']);


        }catch(\Exception $ex){
            return $ex;
            return redirect()->route('dashboard.brands.index')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
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
        try{

            $brand = Brand::find($id);
            if(!$brand){
                return redirect()->route('dashboard.brands.index')->with(['error' => 'this brand not exists']);
            }

            if($brand->photo != ''){
                if(is_file(base_path('public/assets/admin/' . $brand->photo))){
                    unlink(base_path('public/assets/admin/'.$brand->photo));
                }
            }

            $brand->delete();

            return redirect()->route('dashboard.brands.index')->with(['success' => 'تم الحذف بنجاح']);

        }catch(\Exception $ex){
            return $ex;
            return redirect()->route('dashboard.brands.index')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }

    }

    public function status(Request $request){

        $brand = Brand::find($request->id);

        if(!$brand){
            return json_encode([
                'status'    => false,
                'message'   => 'brand not found'
            ]);
        }
        $status = $request->status == 1 ? 1 : 0;

        $brand->update(['status' => $status]);

        return json_encode([
            'status'    => true,
            'message'   => 'brand updared'
        ]);
    }
}
