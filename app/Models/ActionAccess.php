<?php

namespace App\Models;

use App\Models\ExtendModel;

class ActionAccess extends ExtendModel
{
    protected $table = '050';

    public function user() {
        return $this->belongsTo('App\Models\User', 'USUARI', 'COD');
    }

    public function actionRequests() {
        return $this->hasMany('App\Models\ActionRequest', 'CODACC', 'ACCION');
    }

    public function TypeAction() {
        return $this->belongsTo('App\Models\TypeAction', 'ACCION', 'COD');
    }
}
