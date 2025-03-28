<?php

namespace App\Models;

use App\Models\ExtendModel;

class Employee extends ExtendModel
{
    protected $table = "008";

    protected $primaryKey = 'COD';

    public function newQuery()
    {
        return parent::newQuery()->select('COD', 'APE', 'NOM', 'NOMCOM', 'CEDULA', 'TIPDOC', 'EMAIL', 'COMP', 'SUC', 'DTO', 'SECC', 'UNI', 'PUESTO');
    }

    public function company() {
        return $this->belongsTo(Company::class, 'COMP', 'COD');
    }

    public function workstation() {
        return $this->belongsTo(Workstation::class, 'PUESTO', 'COD');
    }

    public function branch() {
        return $this->belongsTo(Branch::class, 'SUC', 'COD');
    }

    public function department() {
        return $this->belongsTo(Department::class, 'DTO', 'COD');
    }

    public function salary_band() {
        return $this->belongsTo(SalaryBand::class, 'BANSAL', 'COD');
    }
}
