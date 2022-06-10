<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $hidden = ['created_at','updated_at'];
    public $timestamps = true;


    public function scopeActive($query){
        return $query->where('status',1);
    }

    public function  scopeSelection($query){
        return $query->select('id', 'name', 'photo','status');
    }


    public function getActive(){
      return   $this->status == 1 ? 'active'  : 'deactive';
    }

    public function getPhoto(){
        return $this->photo != null ? asset('assets/admin/'.$this->photo) : '';
    }

    // Relations
    public function products(){
        return $this->hasMany(Product::class);
    }


}
