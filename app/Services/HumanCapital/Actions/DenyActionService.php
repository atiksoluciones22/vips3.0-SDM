<?php

namespace App\Services\HumanCapital\Actions;
use App\Models\ActionRequest;
use Illuminate\Support\Facades\DB;

class DenyActionService
{
    public function execute($actionRequest)
    {
        try {
            DB::beginTransaction();

            $user = auth()->user();

            $ActionAccesss = $user->actionsAccess()->pluck('NIVAUT', 'ACCION')->toArray();

            $actionCode = $actionRequest['CODACC'];

            $UserLevel = $ActionAccesss[$actionCode];

            ActionRequest::where('COD', $actionRequest['COD'])
                ->where('TRAB', $actionRequest['TRAB'])
                ->update([
                    'NIVAUT' => $UserLevel,
                    'APR' . $UserLevel => $user->COD,
                    'FECNI' . $UserLevel => get_date_formatted(),
                    'PTE' => '',
                    'DENEG' => '*'
                ]);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }
}
