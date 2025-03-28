<?php

namespace App\Services\HumanCapital\Actions;
use Illuminate\Support\Facades\DB;
use App\Services\DBService;
use App\Models\{
    Employee,
    TemporaryEmployee,
    TemporaryDependent,
    Dependent,
    Tip,
    TemporarySalaryCollect,
    EmployeePayrollType,
    TemporaryTraining,
    WorkerTraining,
    TemporaryRequiredLanguage,
    RequiredLanguage,
    EmployeeTemporaryPayrollRecord,
    WorkerConcept,
    TemporaryextraPayrollHigh,
    WorkerExtra,
    TemporaryExtraConceptHigh,
    ExtraWorkerConcept,
    TemporaryVacation,
    EnjoyedVacation,
    TemporaryRouteAssignment,
    TemporaryWorker,
    ActionRequest,
    Action,
    DGT4FileAction,
    JobAnalysis,
    AnalysisByWorker,
    TemporaryTip
};

class EntryService
{
    protected $DBService;

    public function __construct()
    {
        $this->DBService = new DBService;
    }

    public function execute($actionRequest)
    {
        try {
            DB::beginTransaction();

            $user = auth()->user();

            $actionCode = $actionRequest['CODACC'];

            $employee = $actionRequest['TRAB'];

            $employeeFilters = ['TRAB' => $employee];

            $userLevel = $user->getTypeActionLevel($actionCode);

            if ($userLevel == null) {
               return false;
            }

            $actionType = $user->getTypeAction($actionCode);

            $dateNow = get_date_formatted();

            $this->DBService->transferData(
                originModel: TemporaryEmployee::class, destinationModel: Employee::class,
                originWheres: $employeeFilters,
                destinationWheres: ['COD' => 'TRAB'],
                changeArrayKeys: ['TRAB' => 'COD']
            );

            $this->DBService->transferData(
                originModel: TemporaryDependent::class, destinationModel: Dependent::class,
                originWheres: $employeeFilters,
                destinationWheres: ['TRAB' => 'TRAB', 'COD' => 'COD']
            );

            $this->DBService->transferData(
                originModel: TemporaryTip::class, destinationModel: Tip::class,
                originWheres: $employeeFilters,
                destinationWheres: ['TRAB' => 'TRAB','PROPIN' => 'PROPIN']
            );

            $this->DBService->transferData(
                originModel: TemporarySalaryCollect::class, destinationModel: EmployeePayrollType::class,
                originWheres: $employeeFilters,
                destinationWheres: ['TRAB' => 'TRAB', 'TIPNOM' => 'TIPNOM']
            );

            $this->DBService->transferData(
                originModel: TemporaryTraining::class, destinationModel: WorkerTraining::class,
                originWheres: $employeeFilters,
                destinationWheres: ['TRAB' => 'TRAB', 'CURSO', '=', 'CURSO']
            );

            $this->DBService->transferData(
                originModel: TemporaryRequiredLanguage::class, destinationModel: RequiredLanguage::class,
                originWheres: $employeeFilters,
                destinationWheres: ['TRAB' => 'TRAB', 'IDIOMA' => 'IDIOMA']
            );

            $this->DBService->transferData(
                originModel: EmployeeTemporaryPayrollRecord::class, destinationModel: WorkerConcept::class,
                originWheres: $employeeFilters,
                destinationWheres: ['TRAB' => 'TRAB', 'CONCEP' => 'CONCEP']
            );

            $this->DBService->transferData(
                originModel: TemporaryextraPayrollHigh::class, destinationModel: WorkerExtra::class,
                originWheres: $employeeFilters,
                destinationWheres: ['TRAB' => 'TRAB', 'NOMIN' => 'NOMIN']
            );

            $this->DBService->transferData(
                originModel: TemporaryExtraConceptHigh::class, destinationModel: ExtraWorkerConcept::class,
                originWheres: $employeeFilters,
                destinationWheres: ['TRAB' => 'TRAB', 'NOMIN' => 'NOMIN', 'CONCEP' => 'CONCEP']
            );

            $this->DBService->transferData(
                originModel: TemporaryVacation::class, destinationModel: EnjoyedVacation::class,
                originWheres: $employeeFilters,
                destinationWheres: ['TRAB' => 'TRAB', 'COD' => 'COD']
            );

            /*$this->DBService->transferData(
                originModel: TemporaryRouteAssignment::class, destinationModel: TemporaryWorker::class,
                originWheres: $employeeFilters,
                destinationWheres: ['TRA' => 'TRAB'],
                changeArrayKeys: ['COD' => 'TRAB']
            );*/

            ActionRequest::where('TRAB', $employee)->where('COD', $actionRequest['COD'])->update([
                'NIVAUT' => $userLevel,
                'FECNI' . $userLevel => $dateNow,
                'APR' . $userLevel => $user->COD,
                'PTE' => '',
                'APROBA' => '*'
            ]);

            $EmployeeTemporaryPayrollRecord = EmployeeTemporaryPayrollRecord::select(['TRAB', 'CONCEP', 'IMP', 'DIV', 'FORPAG'])
            ->where('TRAB', $employee)->where('CONCEP', 1)->first();

            $temporaryWorker = TemporaryWorker::select('COMP', 'SUC', 'DTO', 'SECC', 'UNI', 'TABPUE', 'PUESTO', 'TABNIV', 'NIVEL', 'BANSAL', 'NOMCOM', 'NOM', 'APE', 'CEDULA', 'TIPDOC','FECNTO', 'SEXO', 'PAISOR', 'TURNO')
            ->where($employeeFilters)->first();

            $this->DBService->insert(
            model: Action::class,
            array: [
                [
                    'TRAB' => $employee,
                    'ACCION' => $actionCode,
                    'TIPINT' => $actionType->CODSEC,
                    'CODSOL' => $actionType->CODUNO,
                    'FECHA' => $dateNow,
                    'COMP' => $temporaryWorker?->COMP,
                    'SUC' => $temporaryWorker?->SUC,
                    'DTO' => $temporaryWorker?->DTO,
                    'SECC' => $temporaryWorker?->SECC,
                    'UNI' => $temporaryWorker?->UNI,
                    'TABPOS' => $temporaryWorker?->TABPUE,
                    'POS' => $temporaryWorker?->PUESTO,
                    'NIVEL' => $temporaryWorker?->NIVEL,
                    'BANSAL' => $temporaryWorker?->BANSAL,
                    'FECING' => $temporaryWorker?->FECING,
                    'FECEFE' => $dateNow,
                    'SUENUE' => $EmployeeTemporaryPayrollRecord?->IMP,
                    'AUTOR' => $user->NOM,
                ]
            ],
            wheres: $employeeFilters);

            $this->DBService->insert(
            model: DGT4FileAction::class,
            array: [
                [
                    'TRAB' => $employee,
                    'FECHA' => $dateNow,
                    'NOMCOM' => $temporaryWorker?->NOMCOM,
                    'NOM' => $temporaryWorker?->NOM,
                    'APE' => $temporaryWorker?->APE,
                    'CEDULA' => $temporaryWorker?->CEDULA,
                    'TIPDOC' => $temporaryWorker?->TIPDOC,
                    'FECNTO' => $temporaryWorker?->FECNTO,
                    'SEXO' => $temporaryWorker?->SEXO,
                    'PAISOR' => $temporaryWorker?->PAISOR,
                    'TABPUE' => $temporaryWorker?->TABPUE,
                    'PUESTO' => $temporaryWorker?->PUESTO,
                    'TURNO' => $temporaryWorker?->TURNO,
                    'ACCION' => $actionType->CODUNO,
                    'SUELDO' => $EmployeeTemporaryPayrollRecord?->IMP,
                    'TIPSUE' => $EmployeeTemporaryPayrollRecord?->FORPAG,
                    'DIVSUE' => $EmployeeTemporaryPayrollRecord?->DIV,
                    'COMP' => $temporaryWorker?->COMP,
                ]
            ],
            wheres: $employeeFilters);

            /*$this->DBService->transferData(
                originModel: JobAnalysis::class, destinationModel: AnalysisByWorker::class,
                originWheres: ['TABPUE' => $temporaryWorker?->TABPUE, 'CODPUE' => $temporaryWorker?->PUESTO],
                destinationWheres: ['TRAB' => 'TRAB', 'ANA' => 'ANA'],
                deleteOrigin: false
            );*/

            Employee::where('COD', $employee)->update([
                "FECSAL" => '0',
                "INACT" => '',
                "SUELDO" => $EmployeeTemporaryPayrollRecord?->IMP,
                "DIVSUL" => $EmployeeTemporaryPayrollRecord?->DIV,
                "TIPSUE" => $EmployeeTemporaryPayrollRecord?->FORPAG
            ]);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }
}
