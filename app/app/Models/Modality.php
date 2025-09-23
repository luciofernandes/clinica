<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modality extends Model
{
    protected $fillable = ['name', 'description', 'created_by', 'updated_by'];
}
