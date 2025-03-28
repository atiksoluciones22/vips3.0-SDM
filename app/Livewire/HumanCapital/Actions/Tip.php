<?php

namespace App\Livewire\HumanCapital\Actions;

use App\Models\Action;
use App\Models\TipType;
use App\Livewire\Extends\ComponentAction;
use App\Services\HumanCapital\Actions\{TipService, DenyActionService};

class Tip extends ComponentAction
{
    public $actionRequest;

    public function approve() {
        $this->response((new TipService)->execute($this->actionRequest));
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


        return view('livewire.human-capital.actions.tip', compact('action'));
    }
}
