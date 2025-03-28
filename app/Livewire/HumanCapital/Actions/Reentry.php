<?php

namespace App\Livewire\HumanCapital\Actions;

use App\Services\DBService;
use App\Livewire\Extends\ComponentAction;
use App\Services\HumanCapital\Actions\{ReentryService, DenyActionService};

class Reentry extends ComponentAction
{
    public $actionRequest;

    public function approve() {
        $this->response((new ReentryService)->execute($this->actionRequest));
        $this->closeAction();
    }

    public function deny() {
        $this->response((new DenyActionService)->execute($this->actionRequest), 'Se ha denegado la solicitud.');
        $this->closeAction();
    }

    public function render()
    {
        $destination = (new DBService)->getDestinationData($this->actionRequest);
        return view('livewire.human-capital.actions.reentry', compact('destination'));
    }
}
