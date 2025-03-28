<?php

namespace App\Services\HumanCapital\Actions;
use Illuminate\Support\Facades\DB;
use App\Services\{MailService, RequestService, DBService};
use App\Models\{
    Action,
    ActionRequest,
    DistributionLeaveBetweenPayroll,
    EqualTo97ForTipsInAnotherPayroll
};

class LicenseService
{
    protected $DBService, $MailService, $RequestService, $date;

    public function __construct()
    {
        $this->DBService = new DBService;
        $this->RequestService = new RequestService;
        $this->MailService = new MailService;
        $this->date = get_date_formatted();;
    }

    public function execute($request)
    {
        try {
            DB::beginTransaction();

            $user = auth()->user();

            $employeeFilters = ['TRAB' => get_array_value($request, 'TRAB')];

            $action = (object) $this->DBService->insert(model: Action::class, array: [
                    [
                        'CODSOL' => get_array_value($request, 'COD'),
                        'TRAB' => get_array_value($request, 'TRAB'),
                        'ACCION' => 50000 + get_array_value($request, 'TIPLM'),
                        'TIPINT' => '5',
                        'FECHA' => $this->date,
                        'NOAUT' => '',
                        'AUTOR' => $user->NOM,
                        'FECEFE' => $this->date,
                        'COMTRA' => get_array_value($request, 'COMTRA') ? '*' : '',
                        'COMRES' => get_array_value($request, 'COMRES') ? '*' : '',
                        'DIAS' => get_array_value($request, 'DIAS'),
                        'TIPDIA' => get_array_value($request, 'TIPDIA'),
                        'DIAFIN' => get_array_value($request, 'DIAFIN'),
                        'APLDES' => get_array_value($request, 'APLDES'),
                        'DESSAL' => get_array_value($request, 'DESSAL'),
                        'DESPRO' => get_array_value($request, 'DESPRO'),
                        'SUBCOD' => get_array_value($request, 'SUBCOD'),
                        'COMP' => get_array_value($request, 'COMP'),
                        'SUC' => get_array_value($request, 'SUC'),
                        'DTO' => get_array_value($request, 'DTO'),
                        'TODNOM' => get_array_value($request, 'TODNOM'),
                        'DIANOM' => get_array_value($request, 'DIANOM'),
                        'TODPRO' => get_array_value($request, 'TODPRO'),
                        'DIAPRO' => get_array_value($request, 'DIAPRO'),
                        'NODIS' => get_array_value($request, 'NODIS'),
                        'TIPLM' => get_array_value($request, 'TIPLM'),
                    ]
                ], wheres: $employeeFilters, returnData: true);

                $this->updateActionRequest($request, $user);

                $this->sendNotifications($request);

                $where = array_merge($employeeFilters,
                [
                    'CODLIC' => get_array_value($request, 'COD'),
                    'TIPNOM' => get_array_value($request, 'TIPNOM'),
                ]);

                DistributionLeaveBetweenPayroll::where($where)->update(
                    [
                        'SEPUDE' => '*',
                        'COD51' => $action->COD,
                    ]
                );

                EqualTo97ForTipsInAnotherPayroll::where($where)->update(
                    [
                        'SEPUDE' => '*',
                        'COD51' => $action->COD,
                    ]
                );

                DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    private function updateActionRequest($request, $user)
    {
        $userLevel = $user->getTypeActionLevel($request['CODACC']);

        ActionRequest::where('TRAB', get_array_value($request, 'TRAB'))
            ->where('COD', get_array_value($request, 'COD'))
            ->update([
                'NIVAUT' => $userLevel,
                'FECNI' . $userLevel => $this->date,
                'APR' . $userLevel => $user->COD,
                'PTE' => '',
                'APROBA' => '*',
                'FECEFE' => $this->date
            ]);
    }

    private function sendNotifications($request)
    {
        $this->MailService->sendResponsible(request: $request, subject: 'Cambio de participación en divisas', message: 'message...');
        $this->MailService->sendEmployee(request: $request, subject: 'Cambio de participación en divisas', message: 'message...');
    }
}
