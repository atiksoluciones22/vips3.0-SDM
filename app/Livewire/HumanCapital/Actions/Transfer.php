<?php

namespace App\Livewire\HumanCapital\Actions;

use App\Livewire\Extends\ComponentAction;
use App\Services\HumanCapital\Actions\{TransferService, DenyActionService};
use App\Services\DBService;

class Transfer extends ComponentAction
{
    public $actionRequest;

    public function approve() {
        $this->response((new TransferService)->execute($this->actionRequest));
        $this->closeAction();
    }

    public function deny() {
        $this->response((new DenyActionService)->execute($this->actionRequest), 'Se ha denegado la solicitud.');
        $this->closeAction();
    }

    public function render()
    {
        $DBService = new DBService;

        $origin = $DBService->getOriginData($this->actionRequest);

        $destination = $DBService->getDestinationData($this->actionRequest);

        return view('livewire.human-capital.actions.transfer', compact('origin', 'destination'));
    }
}
