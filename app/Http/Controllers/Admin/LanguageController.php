<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Admin\Language;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LanguageRequest;

class LanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $languages = Language::selection()->paginate(PAGINATION_COUNT);
        //return $languages;
        return view('dashboard.languages.index', compact('languages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        return view('dashboard.languages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LanguageRequest $request){
        try {

            Language::create($request->except(['_token']));
            return redirect()->route('dashboard.languages')->with(['success' => 'تم حفظ اللغة بنجاح']);
        } catch (\Exception $ex) {
            return $ex;
            return redirect()->route('dashboard.languages')->with(['error' => 'هناك خطا ما يرجي المحاوله فيما بعد']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        $language = Language::select()->find($id);
        if (!$language) {
            return redirect()->route('dashboard.languages')->with(['error' => 'هذه اللغة غير موجوده']);
        }

        return view('dashboard.languages.edit', compact('language'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update($id, LanguageRequest $request){

        try {
            $language = Language::find($id);
            if (!$language) {
                return redirect()->route('dashboard.languages.edit', $id)->with(['error' => 'هذه اللغة غير موجوده']);
            }


            if (!$request->has('active'))
                $request->request->add(['active' => 0]);

            $language->update($request->except('_token'));

            return redirect()->route('dashboard.languages')->with(['success' => 'تم تحديث اللغة بنجاح']);

        } catch (\Exception $ex) {
            return redirect()->route('dashboard.languages')->with(['error' => 'هناك خطا ما يرجي المحاوله فيما بعد']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){

        try {
            $language = Language::find($id);
            if (!$language) {
                return redirect()->route('dashboard.languages', $id)->with(['error' => 'هذه اللغة غير موجوده']);
            }
            $language->delete();

            return redirect()->route('dashboard.languages')->with(['success' => 'تم حذف اللغة بنجاح']);

        } catch (\Exception $ex) {
            return redirect()->route('dashboard.languages')->with(['error' => 'هناك خطا ما يرجي المحاوله فيما بعد']);
        }
    }

    public function status(Request $request){

        $language = Language::find($request->id);

        if(!$language){
            return json_encode([
                'status'    => false,
                'message'   => 'language not found'
            ]);
        }
        $status = $request->status == 1 ? 1 : 0;

        $language->update(['status' => $status]);

        return json_encode([
            'status'    => true,
            'message'   => 'language updared'
        ]);
    }
}
