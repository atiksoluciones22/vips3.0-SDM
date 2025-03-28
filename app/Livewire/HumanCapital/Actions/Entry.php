<?php

namespace App\Livewire\HumanCapital\Actions;

use App\Livewire\Extends\ComponentAction;
use App\Services\HumanCapital\Actions\{EntryService, DenyActionService};
use App\Services\DBService;

class Entry extends ComponentAction
{
    public $actionRequest;

    public function approve() {
        $this->response((new EntryService)->execute($this->actionRequest));
        $this->closeAction();
    }

    public function deny() {
        $this->response((new DenyActionService)->execute($this->actionRequest), 'Se ha denegado la solicitud.');
        $this->closeAction();
    }

    public function render()
    {
        $destination = (new DBService)->getDestinationData($this->actionRequest);
        return view('livewire.human-capital.actions.entry', compact('destination'));
    }
}
