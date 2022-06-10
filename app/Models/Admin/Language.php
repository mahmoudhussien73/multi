<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'abbr',
        'locale',
        'name',
        'direction',
        'status',
        'created_at',
        'updated_at'
    ];

    protected $hidden = ['created_at','updated_at'];
    public $timestamps = true;

    public function scopeActive($query){
        return $query->where('status',1);
    }

    public function  scopeSelection($query){

        return $query->select('id','abbr', 'locale','name', 'direction', 'status');
    }


    public function getActive(){
      return  $this->status == 1 ? 'active'  : 'deactive';
    }
}
