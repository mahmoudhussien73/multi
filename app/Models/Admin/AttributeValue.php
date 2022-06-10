<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $hidden = ['created_at','updated_at','pivot'];
    public $timestamps = true;


    public function attribute(){
        return $this->belongsTo(Attribute::class);
    }

    public function products(){
        return $this->belongsToMany(Product::class,'attribute_value_product');
    }
}
