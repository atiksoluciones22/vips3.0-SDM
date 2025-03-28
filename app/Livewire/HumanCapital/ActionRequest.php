<?php

namespace App\Livewire\HumanCapital;

use App\Models\ActionRequest as ModelsActionRequest;
use App\Livewire\Extends\ExtendComponent;

class ActionRequest extends ExtendComponent
{
    public $search = '';

    protected $listeners = ['refreshActionRequest' => 'refresh'];

    public function refresh()
    {
        $this->render();
    }

    public function showAction($actionRequest, $actionCode){
        $this->dispatch('setActionRequest', $actionRequest, $actionCode);
    }

    public function render()
    {
        $ActionAccesss = auth()->user()->actionsAccess()->pluck('NIVAUT', 'ACCION')->toArray();

        $query = ModelsActionRequest::query()
            ->whereColumn('NIVAPR', '>', 'NIVAUT')
            ->where('PTE', '*');

        if (!empty($ActionAccesss)) {
            $query->where(function($query) use ($ActionAccesss) {
                foreach ($ActionAccesss as $action => $nivel) {
                    $query->orWhere(function($q) use ($action, $nivel) {
                        $q->where('CODACC', $action)
                          ->where('NIVAPR', '<=', $nivel);
                    });
                }
            });
        } else {
            $query->whereRaw('1 = 0');
        }

        $query->with([
            'type_action',
            'applicant',
            'employee',
            'temporaryWorker',
            'level_2_approver',
            'level_3_approver',
            'level_4_approver',
            'level_5_approver'
        ]);

        if ($this->search) {
            $query->where('TRAB', 'like', '%'.$this->search.'%');
        }

        $actionRequests = $query->get();

        return view('livewire.human-capital.action-request', compact('actionRequests'));
    }
}
