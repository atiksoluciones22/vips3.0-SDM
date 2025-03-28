<?php

namespace App\Livewire\Extends;

use Livewire\Component;

class ExtendComponent extends Component
{
    public function response($response, $message = 'La acción fue aprobada con exito.'){
        if($response === true){
            $this->alert('success', $message);
        }else{
            $this->alert('error', 'La acción no pudo ser aprobada.');
        }
    }
}
