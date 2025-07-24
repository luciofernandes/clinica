<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Authorization extends Model
{
     use SoftDeletes;
     protected $dates = ['deleted_at'];

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

    public function getBillingStatusAttribute()
    {
        $valorAutorizado = $this->modalities->sum('total_value');
        $valorFaturado = $this->invoices->sum('amount');
        $valorPago = $this->invoices()->where('status', 'pago')->sum('amount');

        if ($valorFaturado == 0) {
            return 'sem_cobranca';
        }

        if ($valorFaturado > $valorAutorizado) {
            return 'excedente';
        }

        if ($valorFaturado < $valorAutorizado) {
            return 'parcial';
        }

        if ($valorFaturado == $valorAutorizado) {
            return ($valorPago == $valorAutorizado)
                ? 'pago_completo'
                : 'faturado_pendente';
        }

        return 'indefinido';
    }
    public function getAtrasoFinanceiroAttribute()
    {
        $hoje = Carbon::today();

        $validadeVencida = $this->valid_until && Carbon::parse($this->valid_until)->lt($hoje);
        $ultimaSessaoVencida = $this->estimated_end_date && Carbon::parse($this->estimated_end_date)->lt($hoje);

        $statusFinanceiro = $this->billing_status;

        if (($validadeVencida || $ultimaSessaoVencida) && $statusFinanceiro !== 'pago_completo') {
            return true;
        }

        return false;
    }


}
