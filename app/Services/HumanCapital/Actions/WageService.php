<?php

namespace App\Services\HumanCapital\Actions;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\{MailService, RequestService, DBService};
use App\Models\{
    Action,
    Incidence,
    Company,
    Employee,
    ActionRequest,
    TransactionHistory,
    WorkerConcept,
    DGT4FileAction
};

class WageService
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

            if(get_array_value($request, 'TSNUEV') == 0){
                $exchangeRate = get_array_value($request, 'TIPDIA');
            }else{
                $tsnuev = get_array_value($request, 'TSNUEV');

                $exchangeRateMap = [
                    '3' => $tsnuev,
                    '4' => 6,
                    '5' => 7
                ];

                $exchangeRate = get_array_value($exchangeRateMap, $tsnuev);
            }

            $employeeCode = get_array_value($request, 'TRAB');

            $employeeFilters = ['TRAB' => $employeeCode];

            $dateChange = Carbon::createFromFormat('Ymd', get_array_value($request, 'FECEFE'));

            $newCurrency = get_array_value($request, 'DIVNUE');

            $newAmount = get_array_value($request, 'PRONUE');

            $this->DBService->insert(model: Action::class, array: [
                [
                    'TRAB' => $employeeCode,
                    'ACCION' => 30000 + (get_array_value($request, 'CODACC') - 30000),
                    'TIPINT' => '3',
                    'FECHA' => $this->date,
                    'AUTOR' =>  $user->NOM,
                    'COMP' => get_array_value($request, 'COMP'),
                    'SUC' => get_array_value($request, 'SUC'),
                    'DTO' => get_array_value($request, 'DTO'),
                    'SECC' => get_array_value($request, 'SECC'),
                    'UNI' => get_array_value($request, 'UNI'),
                    'POS' => get_array_value($request, 'POS'),
                    'TABPOS' => get_array_value($request, 'TABPOS'),
                    'PRONUE' => $newAmount,
                    'SUEANT' => get_array_value($request, 'SUEANT'),
                    'SUENUE' => get_array_value($request, 'SUENUE'),
                    'DIVORI' => get_array_value($request, 'DIVORI'),
                    'DIVNUE' => $newCurrency,
                    'COMRES' => get_array_value($request, 'COMRES') ? '*' : '',
                    'COMTRA' => get_array_value($request, 'COMTRA') ? '*' : '',
                    'FECEFE' => $dateChange,
                    'FECCAL' => get_array_value($request, 'FECCAL'),
                    'TURORI' => get_array_value($request, 'TURORI'),
                    'TIPDIA' => get_array_value($request, 'TIPDIA'),
                    'DIVCAM' => get_array_value($request, 'DIVCAM'),
                    'TSNUEV' => get_array_value($request, 'TSNUEV'),
                    'NOMNOM' => get_array_value($request, 'NOMNOM'),
                    'ANOPAG' => get_array_value($request, 'ANOPAG'),
                    'MESPAG' => get_array_value($request, 'MESPAG'),
                    'TIPNOM' => get_array_value($request, 'TIPNOM'),
                    'PERPAG' => get_array_value($request, 'PERPAG'),
                ]
            ], wheres: $employeeFilters);


            $this->updateActionRequest($request, $user);

            $this->sendNotifications($request);

            $dateChangeLessOneDay = $dateChange->subDay();

            TransactionHistory::where('FECFIN', 0)->where('CONCEP', 1)->where('TRAB', $employeeCode)->update([
                'FECFIN' => $dateChangeLessOneDay->format('Ymd')
            ]);

            $workerConcept = WorkerConcept::where('TRAB', $employeeCode)->where('CONCEP', 1)->first();

            $this->DBService->insert(model: TransactionHistory::class, array: [
                [
                    'TRAB' => $employeeCode,
                    'CONCEP' => 1,
                    'FECINI' => $dateChange,
                    'FORPAG' => $exchangeRate,
                    'IMP' => $newAmount,
                    'DIV' => $newCurrency,
                    'FECFIN' => '0',
                    'SALNET' => $workerConcept?->SALNET ?? '',
                ]
            ], wheres: array_merge($employeeFilters, ['CONCEP' => 1]), increments: ['LIN']);


            if($exchangeRate == 7){
                Employee::where('COD', $employeeCode)->update([
                   'SUELDO' => $newAmount,
                   'DIVSUL' => $newCurrency,
                   'TIPSUE' => $exchangeRate,
                   'FECCAM' => $dateChange
                ]);
            }else{
                // Calcula el valor inicial del multiplicador
                $multiplier = round(8 * 23.83, 2);

                // Obtiene el empleado basado en el código proporcionado
                $hasEmployee = Employee::where('COD', $employeeCode)->first();

                if ($hasEmployee) {
                    // Obtiene la compañía asociada al empleado
                    $hasCompany = Company::where('COD', $hasEmployee->COMP)->first();

                    if ($hasCompany && !empty($hasCompany->HORSAM)) {
                        // Actualiza el multiplicador basado en los valores de la compañía
                        $multiplier = $hasCompany->HORSAM / 60;
                    }
                }

                Employee::where('COD', $employeeCode)->update([
                    'SUELDO' => $newAmount * $multiplier,
                    'DIVSUL' => $newCurrency,
                    'TIPSUE' => '1',
                    'FECCAM' => $dateChange
                 ]);
            }

            $this->DBService->insert(model: Action::class, array: [
                [
                    'TRAB' => $employeeCode,
                    'CONCEP' => 1,
                    'FORPAG' => $exchangeRate,
                    'IMP' => $newAmount,
                    'DIV' => $newCurrency,
                    'SOLNOM' => '',
                    'TRAEST' => '',
                    'CALSAL' => '*',
                    'SALNET' => get_array_value($request, 'SALNET')
                ]
            ], increments: []);


            $numberDaysApply = 0;

            $typePeriod = 2;

            $typePeriodMap = [
                '5' => 3,
                '2' => 2,
                '3' => 1
            ];

            $employee = Employee::where('COD', $employeeCode)->first();

            $typePeriod = get_array_value($typePeriodMap, $employee?->PERPAG);

            $numberDaysApply = Carbon::createFromFormat('Ymd', get_array_value($request, 'DIAFIN'))
                            ->diffInDays(Carbon::createFromFormat('Ymd', get_array_value($request, 'FECEFE'))
                            ->addDays(3)) - 1;

            if($numberDaysApply > 0){
                $this->DBService->insert(model: Incidence::class, array: [
                    [
                        'TRAB' => $employeeCode,
                        //'CONCEP' => // TODO: Obtener el valor correcto,
                        'EXTRA' => '',
                        'TIPO' => '1',
                        'TIPCAL' => '1',
                        'DIAS' => $numberDaysApply,
                        'HORSAL' => '0',
                        'TEXPRI' => '', // TODO: BUSCAR EL TEXTO DE LA PRIMERA NOMINA
                        'NOMOST' => '',
                        'TIPNOM' => 'G',
                        'COMP' => get_array_value($request, 'COMP'),
                        'SUC' => get_array_value($request, 'SUC'),
                        'DTO' => get_array_value($request, 'DTO'),
                        'SECC' => get_array_value($request, 'SECC'),
                        'UNI' => get_array_value($request, 'UNI'),
                        'ANOPRI' => '',
                        'MESPRI' => ''
                    ]
                ]);

                // $dateChange
                // $typePeriod
                // TODO: ESTUDIAR ESTE METODO:  SaberClaveNomina(FechaCambio,TipoPeriodo);

                $this->DBService->insert(model: Action::class, array: [
                    [
                        'TRAB' => $employeeCode,
                        // 'CONCEP' => // TODO: Obtener el valor correcto,
                        'CLANOM' => '',
                        'DIAS' => $numberDaysApply,
                        'COMP' => get_array_value($request, 'COMP'),
                        'SUC' => get_array_value($request, 'SUC'),
                        'DTO' => get_array_value($request, 'DTO'),
                        'SECC' => get_array_value($request, 'SECC'),
                        'UNI' => get_array_value($request, 'UNI'),
                        'TEXTO' => '', // TODO: DONDE ENCONTRAR ESTE TEXTO
                        'MONTO' => '' // ! TODO:  SueldoDiario := RedondearImporte((SueldoDiarioNuevo - SueldoDiarioAnterior) * NumeroDiasAplicar);
                    ]
                ], wheres: ['LIN' => '1']);
            }


            $this->DBService->insert(model: DGT4FileAction::class, array: [
                [
                    'TRAB' => $employeeCode,
                    'FECHA' => $dateChange,
                    'NOMCOM' => $employee->NOMCOM,
                    'NOM' => $employee->NOM,
                    'APE' => $employee->APE,
                    'CEDULA' => $employee->CEDULA,
                    'TIPDOC' => $employee->TIPDOC,
                    'FECNTO' => $employee->FECNTO,
                    'SEXO' => $employee->SEXO,
                    'PAISOR' => $employee->PAISOR,
                    'NOMPAI' => '', // ! TODO: if LeerRegistro(908,ValorCampo(8,'PAISOR')) = True then (NO EXISTE la TABLA 908)
                    'TABPUE' => $employee->TABPUE,
                    'PUESTO' => $employee->PUESTO,
                    'TURNO' => $employee->TURNO,
                    'ACCION' => '3',
                    'SUELDO' => $newAmount,
                    'TIPSUE' => $exchangeRate,
                    'DIVSUE' => $newCurrency,
                    'COMP' => get_array_value($request, 'COMP')
                ]
            ], wheres: $employeeFilters);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

   private function redondearImporte(float $numero): float {
        $result = abs($numero);

        $result = $result * 100 + 0.5;
        $result = floor($result + 0.0001);
        $result = $result / 100;

        if ($numero < 0) {
            $result = $result * -1;
        }

        return $result;
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
