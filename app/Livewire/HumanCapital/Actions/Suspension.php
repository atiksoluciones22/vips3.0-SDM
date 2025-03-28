<?php

namespace App\Livewire\HumanCapital\Actions;

use App\Models\Action;
use App\Livewire\Extends\ComponentAction;
use App\Services\HumanCapital\Actions\{SuspensionService, DenyActionService};

class Suspension extends ComponentAction
{
    public $actionRequest;

    public function approve() {
        $this->response((new SuspensionService)->execute($this->actionRequest));
        $this->closeAction();
    }

    public function deny() {
        $this->response((new DenyActionService)->execute($this->actionRequest), 'Se ha denegado la solicitud.');
        $this->closeAction();
    }

    public function render()
    {
        $action = Action::where('COD', get_array_value($this->actionRequest, 'COD'))
            ->where('TRAB', get_array_value($this->actionRequest, 'TRAB'))->first();

        return view('livewire.human-capital.actions.suspension', compact('action'));
    }
}
