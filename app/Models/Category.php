<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable=['name','admin_id', 'description', 'image'];
    
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
