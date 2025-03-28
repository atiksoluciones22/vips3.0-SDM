<?php

namespace App\Livewire\HumanCapital\Actions;

use App\Services\DBService;
use App\Models\CompanySchedule;
use App\Livewire\Extends\ComponentAction;
use App\Services\HumanCapital\Actions\{ScheduleChangeService, DenyActionService};

class ScheduleChange extends ComponentAction
{
    public $actionRequest;

    public function approve() {
        $this->response((new ScheduleChangeService)->execute($this->actionRequest));
        $this->closeAction();
    }

    public function deny() {
        $this->response((new DenyActionService)->execute($this->actionRequest), 'Se ha denegado la solicitud.');
        $this->closeAction();
    }

    public function render()
    {
        $DBService = new DBService;

        $originSchedule = $DBService->getEntityData(CompanySchedule::class, ['COD' => get_array_value($this->actionRequest, 'TURORI'), 'COMP' => get_array_value($this->actionRequest, 'COMP')]);

        $destinationSchedule = $DBService->getEntityData(CompanySchedule::class, ['COD' => get_array_value($this->actionRequest, 'TURNUE'), 'COMP' => get_array_value($this->actionRequest, 'COMP')]);

        return view('livewire.human-capital.actions.schedule-change', compact('originSchedule', 'destinationSchedule'));
    }
}
