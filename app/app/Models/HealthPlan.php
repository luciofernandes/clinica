<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HealthPlan extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public function authorizations()
    {
        return $this->hasMany(Authorization::class);
    }
}
