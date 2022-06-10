<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $hidden = ['created_at','updated_at','pivot'];
    public $timestamps = true;

    public function values(){
        return $this->hasMany(AttributeValue::class);
    }
}
