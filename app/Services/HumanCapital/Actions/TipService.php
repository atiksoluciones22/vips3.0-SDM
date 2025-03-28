<?php

namespace App\Services\HumanCapital\Actions;
use Illuminate\Support\Facades\DB;
use App\Models\{Action, Tip, ActionRequest};
use App\Services\{MailService, RequestService, DBService};

class TipService
{
    protected $DBService, $MailService, $RequestService, $date;

    public function __construct()
    {
        $this->DBService = new DBService;
        $this->RequestService = new RequestService;
        $this->MailService = new MailService;
        $this->date = get_date_formatted();
    }

    public function execute($request)
    {
        try {
            DB::beginTransaction();

            $user = auth()->user();

            $employeeFilters = ['TRAB' => get_array_value($request, 'TRAB')];

            $tipCode = get_array_value($request, 'type_action.CODSEC'); // TODO: VERIFICAR SI ESE CODIGO ES EL CORRECTO (si pongo otro tipo se propina aqui llega el codigo correspondiente o no).

            $this->DBService->insert(model: Action::class, array: [
                [
                    'TRAB' => get_array_value($request, 'TRAB'),
                    'ACCION' => 40000 + $tipCode,
                    'TIPINT' => '4',
                    'FECHA' => $this->date,
                    'NOAUT' => '',
                    'AUTOR' => $user->NOM,
                    'FECEFE' => $this->date,
                    'COMTRA' => get_array_value($request, 'COMTRA') ? '*' : '',
                    'COMRES' => get_array_value($request, 'COMRES') ? '*' : '',
                    'PROORI' => get_array_value($request, 'PROORI'),
                    'DIVORI' => get_array_value($request, 'DIVORI'),
                    'PRONUE' => get_array_value($request, 'PRONUE'),
                    'DIVNUE' => get_array_value($request, 'DIVNUE'),
                    'CODSOL' => get_array_value($request, 'COD')
                ]
            ], wheres: $employeeFilters);

            $this->updateActionRequest($request, $user);

            $this->sendNotifications($request);

            Tip::where(array_merge($employeeFilters, ['PROPIN' => $tipCode]))->delete();

            $this->DBService->insert(model: Tip::class, array: [
                [
                    'TRAB' => get_array_value($request, 'TRAB'),
                    'PROPIN' => $tipCode,
                    'NUMERO' => get_array_value($request, 'PRONUE'),
                    'DIV' => get_array_value($request, 'DIVNUE'),
                ]
            ], wheres: $employeeFilters, increments: []);

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
