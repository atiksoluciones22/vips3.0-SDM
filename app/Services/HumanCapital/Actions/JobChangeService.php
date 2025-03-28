<?php

namespace App\Services\HumanCapital\Actions;
use Illuminate\Support\Facades\DB;
use App\Services\{MailService, RequestService, DBService};
use App\Models\{
    Action,
    ActionRequest,
    Workstation,
    JobAnalysis,
    Employee,
    AnalysisByWorker
};

class JobChangeService
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

            $workstation = Workstation::where('COD', get_array_value($request, 'PUETRA'))->first();

            Employee::where('COD', get_array_value($request, 'TRAB'))->update([
                'PUESTO' => $workstation->COD,
                'NOMPUE' => $workstation->NOM
            ]);

            $this->DBService->insert(model: Action::class, array: [
                [
                    'TRAB' => get_array_value($request, 'TRAB'),
                    'ACCION' => \TypeActions::JOB_CHANGE->value * 10000,
                    'TIPINT' => \TypeActions::JOB_CHANGE->value,
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

            $analisisIds = JobAnalysis::where('TABPUE', $workstation->TABPUE)
                ->where('PUESTO', $workstation->COD)
                ->pluck('ANA');

            $analisisIds->each(function ($analysis) use ($employee) {
                AnalysisByWorker::where('TRAB', $employee)->where('ANA', $analysis)->delete();

                AnalysisByWorker::create([
                    'TRAB' => $employee,
                    'ANA' => $analysis,
                ]);
            });

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
        $this->MailService->sendResponsible(request: $request, subject: 'Cambio de puesto de trabajo', message: 'message...');
        $this->MailService->sendEmployee(request: $request, subject: 'Cambio de puesto de trabajo', message: 'message...');
    }
}
