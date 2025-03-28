<?php

namespace App\Livewire\Extends;

use App\Livewire\Extends\ExtendComponent;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ComponentAction extends ExtendComponent
{
    use LivewireAlert;

    public function closeAction($refresh = true){
        $this->dispatch('closeAction', $refresh);
    }
}
