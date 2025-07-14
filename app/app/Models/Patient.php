<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TracksUserActions;

class Patient extends Model
{
    use HasFactory, TracksUserActions;

    protected $fillable = [
        'name',
        'cpf',
        'created_by',
        'updated_by',
    ];
}
