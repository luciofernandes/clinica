<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Authorization extends Model
{
    protected $fillable = [
        'patient_id',
        'health_plan_id',
        'authorization_number',
        'authorization_date',
        'authorization_expiration_date',
        'estimated_end_date',
        'external_enrollment_link',
        'created_by',
        'updated_by',
    ];
    public function modalities()
    {
        return $this->hasMany(AuthorizationModality::class);
    }

    public function files()
    {
        return $this->hasMany(AuthorizationFile::class);
    }
    public function invoices()
    {
        return $this->hasMany(\App\Models\Invoice::class);
    }
    public function healthPlan()
    {
        return $this->belongsTo(HealthPlan::class);
    }
    public function patient()
    {
        return $this->belongsTo(\App\Models\Patient::class);
    }


}
