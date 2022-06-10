<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;


    define('PAGINATION_COUNT',10);
    /**
     * Tables
     *
     * @return array
     * @var string
     */

     if(!function_exists('tables')){

         function tables(){
             $tables_in_db = DB::select('SHOW TABLES');
             $db = "Tables_in_".env('DB_DATABASE');
             $tables = [];
             foreach($tables_in_db as $table){
                if(in_array($table->{$db}, ['failed_jobs','migrations','password_resets','personal_access_tokens'])){
                    continue;
                }
                $tables[] = $table->{$db};
             }

             return $tables;
         }
     }

     /**
     * get_languages
     *
     * @return array
     * @var string
     */
    if(!function_exists('get_languages')){
        function get_languages(){
            return \App\Models\Admin\Language::selection()->active()->get();
        }
    }

    /**
     * get_default_lang
     *
     * @return array
     * @var string
     */
    if(!function_exists('get_default_lang')){
        function get_default_lang(){
            return   Config::get('app.locale');
        }
    }

    /**
     * uploadImage
     *
     * @return array
     * @var string
     */
    if(!function_exists('uploadImage')){
        function uploadImage($folder, $image){
            $image->store('/', $folder);
            $filename = $image->hashName();
            $path = 'images/' . $folder . '/' . $filename;
            return $path;
        }
    }

    /**
     * uploadVideo
     *
     * @return array
     * @var string
     */
    if(!function_exists('uploadVideo')){
        function uploadVideo($folder, $video){
            $video->store('/', $folder);
            $filename = $video->hashName();
            $path = 'video/' . $folder . '/' . $filename;
            return $path;
        }
    }

