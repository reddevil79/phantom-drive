<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $table = 'mst_devices';
    protected $fillable = ['name', 'serial', 'ip', 'area'];
    protected $dates = ['created_at', 'updated_at'];
}
