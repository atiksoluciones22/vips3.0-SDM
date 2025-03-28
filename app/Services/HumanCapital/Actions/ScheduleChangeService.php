<?php

namespace App\Services\HumanCapital\Actions;
use Illuminate\Support\Facades\DB;
use App\Services\{MailService, RequestService, DBService};
use App\Models\{
    Action,
    ActionRequest,
    Employee
};

class ScheduleChangeService
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

            $employee = get_array_value($request, 'TRAB');

            $employeeFilters = ['TRAB' => $employee];

            Employee::where('COD', get_array_value($request, 'TRAB'))->update([
                'TURNO' => get_array_value($request, 'TURNUE'),
            ]);

            $this->DBService->insert(model: Action::class, array: [
                [
                    'TRAB' => get_array_value($request, 'TRAB'),
                    'ACCION' => \TypeActions::SCHEDULE_CHANGE->value * 10000,
                    'TIPINT' => \TypeActions::SCHEDULE_CHANGE->value,
                    'FECHA' => $this->date,
                    'NOAUT' => '',
                    'AUTOR' => $user->NOM,
                    'FECEFE' => $this->date,
                    'COMTRA' => get_array_value($request, 'COMTRA') ? '*' : '',
                    'COMRES' => get_array_value($request, 'COMRES') ? '*' : '',
                    'TABPUE' => get_array_value($request, 'TABPUE'),
                    'PUEORI' => get_array_value($request, 'PUEORI'),
                    'PUEDES' => get_array_value($request, 'PUEDES'),
                    'CODSOL' => get_array_value($request, 'COD')
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
        $this->MailService->sendResponsible(request: $request, subject: 'Cambio de turno de trabajo', message: 'message...');
        $this->MailService->sendEmployee(request: $request, subject: 'Cambio de turno de trabajo', message: 'message...');
    }
}
