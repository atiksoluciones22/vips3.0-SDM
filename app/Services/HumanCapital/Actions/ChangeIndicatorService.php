<?php

namespace App\Services\HumanCapital\Actions;
use Illuminate\Support\Facades\DB;

class ChangeIndicatorService
{
    public function execute($actionRequest)
    {
        try {
            DB::beginTransaction();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }
}
