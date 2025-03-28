<?php

namespace App\Services\HumanCapital\Actions;

use Illuminate\Support\Facades\DB;
use App\Services\{MailService, RequestService, DBService};
use App\Models\{Action, ActionRequest};

class AdmonitionService
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

            $data = $this->RequestService->getActionData($request, ['TIPINT' => \TypeActions::ADMONITION->value]);

            $this->DBService->insert(model: Action::class, array: [$data], wheres: $employeeFilters);

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
        $this->MailService->sendResponsible(request: $request, subject: 'Amonestación', message: 'message...');
        $this->MailService->sendEmployee(request: $request, subject: 'Amonestación', message: 'message...');
    }
}
