<?php

namespace App\Models\Admin;

use App\Models\Admin;
use App\Models\Admin\Permission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['name','key','color'];

    protected $dates = ['deleted_at'];

    public function permission(){
        return $this->belongsToMany(Permission::class);
    }

    public function admin(){
        return $this->hasMany(Admin::class);
    }

    public function hasPermission($key){
        return $this->permission->contains('key',$key);
    }

    public function adminTrashed(){
        return $this->hasMany(Admin::class)->onlyTrashed();
    }
}
