<?php

namespace App\Models\Admin;

use App\Observers\CategoryObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $hidden = ['created_at','updated_at'];
    public $timestamps = true;

    protected static function boot(){
        parent::boot();
        Category::observe(CategoryObserver::class);
    }


    public function scopeActive($query){
        return $query->where('status',1)->where('translation_lang',get_default_lang());
    }

    public function  scopeSelection($query){
        return $query->select('id', 'description','title', 'photo','status');
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


    // RELATIONS
    public function childrens(){
        return $this->hasMany(self::class, 'translation_of');
    }

    public function _parent(){
        return $this->belongsTo(self::class, 'translation_of');
    }

    public function products(){
        return $this->hasMany(Product::class);
    }
}
