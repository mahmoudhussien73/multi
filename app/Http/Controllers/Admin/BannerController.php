<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Str;
use App\Models\Admin\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BannerRequest;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banners = Banner::where('translation_lang',get_default_lang())->get();
        return view('dashboard.banners.index', compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.banners.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BannerRequest $request)
    {
        //return $request;
        try{
            // Convert to collect
            $banners = collect($request->banner);

            // filter values by current default language
            $filter = $banners->filter(function($value,$key){
                return $value['translation_lang'] == get_default_lang();
            });

            $default_banner = array_values($filter->all())[0];

            //upload image

            $filePath = uploadImage('banners', $default_banner['photo']);

            DB::beginTransaction();

            $default_banner_id = Banner::insertGetId([
                'translation_lang' => $default_banner['translation_lang'],
                'translation_of' => 0,
                'description' => $default_banner['description'],
                'title' => $default_banner['title'],
                'slug' => Str::slug($default_banner['title']),
                'photo' => $filePath
            ]);

            // filter values by other language
            $other_banners = $banners->filter(function ($value, $key) {
                return $value['translation_lang'] != get_default_lang();
            });

            if (isset($other_banners) && $other_banners->count()) {

                $categories_arr = [];
                foreach ($other_banners as $category) {
                    $categories_arr[] = [
                        'translation_lang' => $category['translation_lang'],
                        'translation_of' => $default_banner_id,
                        'description' => $category['description'],
                        'title' => $category['title'],
                        'slug' => Str::slug($category['title']),
                        'photo' => uploadImage('banners', $category['photo']),
                    ];
                }

                Banner::insert($categories_arr);
            }

            DB::commit();

            return redirect()->route('dashboard.banners.index')->with(['success' => 'تم الحفظ بنجاح']);
        }catch(\Exception $ex){
            DB::rollback();
            return $ex;
            return redirect()->route('dashboard.banners.index')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
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

        $banner = Banner::with('childrens')->find($id);
        if(!$banner){
            return redirect()->route('dashboard.banners.index')->with(['error' => 'this banner not exists']);
        }

        return view('dashboard.banners.edit', compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, BannerRequest $request)
    {

        try{
            $banner = Banner::find($id);

            if(!$banner){
                return redirect()->route('dashboard.banners.index')->with(['error' => 'Banner not exists']);
            }


            $collect = collect($request->banner)[0];

             $photo_path = $banner->photo;
             if(isset($collect['photo'])){
                 if($banner->photo != ''){
                    if(is_file(base_path('public/assets/admin/' . $banner->photo))){
                        unlink(base_path('public/assets/admin/'.$photo_path));
                    }
                 }
                $photo_path = uploadImage('banners',$collect['photo']);
             }


             $banner->update([
                 'title'     => $collect['title'],
                 'status'   => $collect['status'] ?? 0,
                 'slug'     => Str::slug($collect['title']),
                 'photo'    => $photo_path
             ]);

            return redirect()->route('dashboard.banners.index')->with(['success' => 'تم التحديث بنجاح']);

        }catch(\Exception $ex){
            return $ex;
            return redirect()->route('dashboard.banners.index')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $banner = Banner::with('childrens')->find($id);
        if(!$banner){
            return redirect()->route('dashboard.banners.index')->with(['error' => 'this banner not exists']);
        }

        if($banner->photo != ''){
            if(is_file(base_path('public/assets/admin/' . $banner->photo))){
                unlink(base_path('public/assets/admin/'.$banner->photo));
            }
         }

        if(!$banner->translation_of){
            $banner->childrens()->delete();
        }

        $banner->delete();
        return redirect()->route('dashboard.banners.index')->with(['success' => 'تم الحذف بنجاح']);
    }

    public function status(Request $request){

        $banner = Banner::find($request->id);

        if(!$banner){
            return json_encode([
                'status'    => false,
                'message'   => 'banner not found'
            ]);
        }
        $status = $request->status ? 1 : 0;

        $banner->update(['status' => $status]);

        return json_encode([
            'status'    => true,
            'message'   => 'banner updared'
        ]);
    }
}
