<?php

namespace App\Livewire\HumanCapital\Actions;

use App\Livewire\Extends\ComponentAction;
use App\Services\HumanCapital\Actions\{AdmonitionService, DenyActionService};

class Abmonition extends ComponentAction
{
    public $actionRequest;

    public function approve() {
        $this->response((new AdmonitionService)->execute($this->actionRequest));
        $this->closeAction();
    }

    public function deny() {
        $this->response((new DenyActionService)->execute($this->actionRequest), 'Se ha denegado la solicitud.');
        $this->closeAction();
    }

    public function render()
    {
        return view('livewire.human-capital.actions.admonition');
    }
}
