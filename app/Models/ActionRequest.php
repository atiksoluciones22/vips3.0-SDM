<?php

namespace App\Models;

use App\Models\{Employee, ExtendModel};

class ActionRequest extends ExtendModel
{
    protected $table = "060";

    public function temporaryWorker()
    {
        return $this->belongsTo(TemporaryWorker::class, 'TRAB', 'TRAB');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'TRAB', 'COD');
    }

    public function applicant() {
        return $this->belongsTo(User::class, 'SOLIC', 'COD');
    }

    public function level_2_approver() {
        return $this->belongsTo(User::class, 'APR2', 'COD');
    }

    public function level_3_approver() {
        return $this->belongsTo(User::class, 'APR3', 'COD');
    }

    public function level_4_approver() {
        return $this->belongsTo(User::class, 'APR4', 'COD');
    }

    public function level_5_approver() {
        return $this->belongsTo(User::class, 'APR5', 'COD');
    }

    public function type_action() {
        return $this->belongsTo(TypeAction::class, 'CODACC', 'COD');
    }
}
