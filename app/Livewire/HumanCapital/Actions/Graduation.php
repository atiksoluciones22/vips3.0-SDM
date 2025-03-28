<?php

namespace App\Livewire\HumanCapital\Actions;

use App\Models\{Action, OutputType, OutputType244};
use App\Livewire\Extends\ComponentAction;
use App\Services\HumanCapital\Actions\{GraduationService, DenyActionService};

class Graduation extends ComponentAction
{
    public $actionRequest;

    public function approve() {
        $this->response((new GraduationService)->execute($this->actionRequest));
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

        $outputType = OutputType::where('COD', get_array_value($this->actionRequest, 'TIPSAL'))->first();

        $outputType244 = OutputType244::where('COD', get_array_value($this->actionRequest, 'CLASAL'))->first();

        return view('livewire.human-capital.actions.graduation', compact('action', 'outputType', 'outputType244'));
    }
}
