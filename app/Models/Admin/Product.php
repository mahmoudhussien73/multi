<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $hidden = ['created_at','updated_at'];
    public $timestamps = true;


    public function scopeActive($query){
        return $query->where('status',1);
    }

    public function  scopeSelection($query){
        return $query->select('id', 'name', 'price','status');
    }

    public function scopeParent($query){
        return $query->whereNull('translation_of');
    }
    public function scopeChild($query){
        return $query->whereNotNull('translation_of');
    }


    public function getStatus(){
      return   $this->status == 1 ? 'active'  : 'deactive';
    }

    public function getPhoto(){
        return $this->photo != null ? asset('assets/admin/'.$this->photo) : '';
    }

    // public function setPhotoAttribute($val){
    //     return $this->attributes['photo'] = json_encode($val);
    // }


    // RELATIONS
    public function childrens(){
        return $this->hasMany(self::class, 'translation_of');
    }

    public function _parent(){
        return $this->belongsTo(self::class, 'translation_of');
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }
    public function brand(){
        return $this->belongsTo(Brand::class);
    }

    public function attributes(){
        return $this->belongsToMany(AttributeValue::class,'attribute_value_product');
    }

    public function relateds(){
        return $this->hasMany(self::class,'category_id', 'category_id');
    }
}

