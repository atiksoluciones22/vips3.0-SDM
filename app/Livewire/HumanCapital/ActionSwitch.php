<?php

namespace App\Livewire\HumanCapital;

use App\Livewire\Extends\ExtendComponent;

class ActionSwitch extends ExtendComponent
{
    public $actionRequest, $actionCode;

    protected $listeners = ['setActionRequest' => 'setActionRequest', 'closeAction' => 'closeAction'];

    public function closeAction($refresh = true) {
        $this->actionRequest = null;
        $this->actionCode = null;
        if($refresh){
            $this->dispatch('refreshActionRequest');
        }
    }

    public function setActionRequest($actionRequest, $actionCode) {
        $this->actionRequest = $actionRequest;
        $this->actionCode = $actionCode;
    }

    public function render()
    {
        return view('livewire.human-capital.action-switch');
    }
}
