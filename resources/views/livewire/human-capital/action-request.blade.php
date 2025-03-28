<div>
    <div class="tool">
        <h1 class="title">Acciones</h1>

        <div class="search">
            <svg data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z">
                </path>
            </svg>
            <input type="text" class="search" wire:model="search" wire:change="$refresh" placeholder="Buscar...">
        </div>
    </div>

    <div class="data-table">
        @if (count($actionRequests) > 0)
            <div class="data-table-header">
                <div class="data-table-row">
                    <div class="data-table-cell">Código</div>
                    <div class="data-table-cell">Trabajador</div>
                    <div class="data-table-cell">Solicitado</div>
                    <div class="data-table-cell">Acción</div>
                    <div class="data-table-cell">Solicitante</div>
                </div>
            </div>

            <div class="data-table-body">
                @foreach ($actionRequests as $actionRequest)
                    <div class="data-table-row"
                        wire:click="showAction({{ $actionRequest }}, {{ $actionRequest->type_action->CODUNO }})">
                        <div class="data-table-cell">{{ $actionRequest->TRAB }}</div>
                        <div class="data-table-cell">{{ $actionRequest->employee?->NOMCOM ?? $actionRequest->temporaryWorker->NOMCOM }}</div>
                        <div class="data-table-cell">
                            {{ set_date_format($actionRequest->FECSOL) }}
                        </div>
                        <div class="data-table-cell">{{ $actionRequest->type_action->NOM }}</div>
                        <div class="data-table-cell">{{ $actionRequest->applicant?->NOM }}</div>
                    </div>
                @endforeach
            </div>
        @else
            <livewire:components.without-result />
        @endif
    </div>

    <div wire:loading>
        <livewire:components.loader />
    </div>

    <livewire:human-capital.action-switch />
</div>
