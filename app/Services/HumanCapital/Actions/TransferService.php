<?php

namespace App\Services\HumanCapital\Actions;
use Illuminate\Support\Facades\DB;
use App\Models\{Action, ActionRequest};
use App\Services\{MailService, RequestService, DBService};

class TransferService
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
                    'CODSOL' => get_array_value($request, 'COD'),


                    'COMP' => get_array_value($request, 'COMP'),
                    'SUC' => get_array_value($request, 'SUC'),
                    'DTO' => get_array_value($request, 'DTO'),
                    'SECC' => get_array_value($request, 'SECC'),
                    'UNI' => get_array_value($request, 'UNI'),


                    // TODO: BUSCAR ESTOS DATOS:
                    /*'COMPOR' => get_array_value($request, 'COMPOR'),
                    'SUCORI' => get_array_value($request, 'SUCORI'),
                    'DTOORI' => get_array_value($request, 'DTOORI'),
                    'SECCOR' => get_array_value($request, 'SECCOR'),
                    'UNIORI' => get_array_value($request, 'COD') */
                ]
            ], wheres: $employeeFilters);

            $this->updateActionRequest($request, $user);

            $this->sendNotifications($request);

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
