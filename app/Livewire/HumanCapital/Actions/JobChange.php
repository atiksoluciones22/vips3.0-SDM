<?php

namespace App\Livewire\HumanCapital\Actions;

use App\Models\Workstation;
use App\Livewire\Extends\ComponentAction;
use App\Services\HumanCapital\Actions\{JobChangeService, DenyActionService};
use App\Services\DBService;

class JobChange extends ComponentAction
{
    public $actionRequest;

    public function approve() {
        $this->response((new JobChangeService)->execute($this->actionRequest));
        $this->closeAction();
    }

    public function deny() {
        $this->response((new DenyActionService)->execute($this->actionRequest), 'Se ha denegado la solicitud.');
        $this->closeAction();
    }

    public function render()
    {
        $DBService = (new DBService);

        $originWorkstation = $DBService->getEntityData(Workstation::class, ['COD' => get_array_value($this->actionRequest, 'employee.PUESTO')], ['NOM']);

        $destinationWorkstation = $DBService->getEntityData(Workstation::class, ['COD' =>  get_array_value($this->actionRequest, 'PUETRA')], ['NOM']);

        return view('livewire.human-capital.actions.job-change', compact('originWorkstation', 'destinationWorkstation'));
    }
}
