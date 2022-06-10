<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Admin\Category;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::where('translation_lang',get_default_lang())->selection()->get();
        return view('dashboard.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {

        try{
            // Convert to collect
            $categories = collect($request->category);

            // filter values by current default language
            $filter = $categories->filter(function($value,$key){
                return $value['translation_lang'] == get_default_lang();
            });

            $default_category = array_values($filter->all())[0];

            //upload image

            $filePath = uploadImage('categories', $default_category['photo']);

            DB::beginTransaction();

            $default_category_id = Category::insertGetId([
                'translation_lang' => $default_category['translation_lang'],
                'translation_of' => 0,
                'description' => $default_category['description'],
                'title' => $default_category['title'],
                'status' => $default_category['status'] ?? 0,
                'slug' => Str::slug($default_category['title']),
                'photo' => $filePath
            ]);

            // filter values by other language
            $other_categories = $categories->filter(function ($value, $key) {
                return $value['translation_lang'] != get_default_lang();
            });

            if (isset($other_categories) && $other_categories->count()) {

                $categories_arr = [];
                foreach ($other_categories as $category) {
                    $categories_arr[] = [
                        'translation_lang' => $category['translation_lang'],
                        'translation_of' => $default_category_id,
                        'description' => $category['description'],
                        'title' => $category['title'],
                        'status'    => $category['status'] ?? 0,
                        'slug' => Str::slug($category['title']),
                        'photo' => uploadImage('categories', $category['photo']),
                    ];
                }

                Category::insert($categories_arr);
            }

            DB::commit();

            return redirect()->route('dashboard.categories.index')->with(['success' => 'تم الحفظ بنجاح']);
        }catch(\Exception $ex){
            DB::rollback();
            return $ex;
            return redirect()->route('dashboard.categories.index')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
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
        $category = Category::with('childrens')->find($id);
        if(!$category){
            return redirect()->route('dashboard.categories.index')->with(['error' => 'this category not exists']);
        }

        return view('dashboard.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, CategoryRequest $request)
    {
        //return $request;
        try{
            $category = Category::find($id);

            if(!$category){
                return redirect()->route('dashboard.categories.index')->with(['error' => 'category not exists']);
            }

            $collect = collect($request->category)[0];

            $photo_path = $category->photo;

            if(isset($collect['photo'])){
                if($category->photo != ''){
                if(is_file(base_path('public/assets/admin/' . $category->photo))){
                    unlink(base_path('public/assets/admin/'.$photo_path));
                }
                }
            $photo_path = uploadImage('categories',$collect['photo']);
            }


            $category->update([
                'title'         => $collect['title'],
                'description'   => $collect['description'],
                'parent_id'     => $collect['parent_id'] ?? 0,
                'status'        => $collect['status'] ?? 0,
                'slug'          => Str::slug($collect['title']),
                'photo'         => $photo_path
            ]);

            return redirect()->route('dashboard.categories.index')->with(['success' => 'تم التحديث بنجاح']);

        }catch(\Exception $ex){
            return $ex;
            return redirect()->route('dashboard.categories.index')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
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
        $category = Category::with('childrens')->find($id);
        if(!$category){
            return redirect()->route('dashboard.categories.index')->with(['error' => 'this category not exists']);
        }

        if($category->photo != ''){
            if(is_file(base_path('public/assets/admin/' . $category->photo))){
                unlink(base_path('public/assets/admin/'.$category->photo));
            }
         }

        if(!$category->translation_of){
            $category->childrens()->delete();
        }

        $category->delete();
        return redirect()->route('dashboard.categories.index')->with(['success' => 'تم الحذف بنجاح']);
    }

    public function status(Request $request){

        $category = Category::find($request->id);

        if(!$category){
            return json_encode([
                'status'    => false,
                'message'   => 'category not found'
            ]);
        }
        $status = $request->status ? 1 : 0;

        $category->update(['status' => $status]);

        return json_encode([
            'status'    => true,
            'message'   => 'category updared'
        ]);
    }

    public function newcategory(CategoryRequest $request){
        //return $request;
        try{
            $category = collect($request->category)[0];

            $filePath = uploadImage('categories', $category['photo']);

            Category::insert([
                'translation_lang' => $category['translation_lang'],
                'translation_of' => $category['translation_of'],
                'description' => $category['description'],
                'title' => $category['title'],
                'status' => $category['status'] ?? 0,
                'slug' => Str::slug($category['title']),
                'photo' => $filePath
            ]);
            return redirect()->route('dashboard.categories.index')->with(['success' => 'تم التحديث بنجاح']);

        }catch(\Exception $ex){
            return $ex;
            return redirect()->route('dashboard.categories.index')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }
    }

    public function sort(){
        $categories = Category::where('translation_lang',get_default_lang())->selection()->get();
        return view('dashboard.categories.sort', compact('categories'));
    }
}
