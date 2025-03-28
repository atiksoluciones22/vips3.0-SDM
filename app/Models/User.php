<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $connection = 'sqlsrv_sdm';

    protected $primaryKey = 'COD';

    public $timestamps = false;

    protected $table = "002";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'NOM',
        'CLA',
        'COD'
    ];

    protected $hidden = [
        'CLA',
    ];

    /**
     * Perform a model query with a given select columns.
     *
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function newQuery()
    {
        return parent::newQuery()->select('COD', 'NOM');
    }

    public function actionsAccess() {
        return $this->hasMany(ActionAccess::class, 'USUARI', 'COD');
    }

    public function getTypeAction($actionCode) {
        return TypeAction::where('COD', $actionCode)->first();
    }

    public function getTypeActionLevel($actionCode) {
        $typeAction = $this->getTypeAction($actionCode);

        if ($typeAction->NOAPRO != '*') {
            $actionAccess = $this->actionsAccess()->where('ACCION', $actionCode)->first();
            return $actionAccess ? $actionAccess->NIVAUT : null;
        }

        return 5;
    }
}
