<div class="action-window">
    <div class="action-body">
        <div class="content-header">
            <svg wire:click="closeAction(false)" class="btn-close" data-slot="icon" fill="none" stroke-width="1.5"
                stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"></path>
            </svg>
        </div>

        <div class="content-body">
            <h2 class="title">detalle de la acción ({{ get_array_value($actionRequest, 'type_action.NOM') }}):</h2>

            @yield('content')

            <div class="approval-request">
                <p>Solicitada el dia {{ set_date_format($actionRequest['FECSOL']) }} por {{ get_array_value($actionRequest, 'applicant.NOM') }}</p>
                <ul>
                    @php
                        $levels = [
                            2 => 'nivel 2',
                            3 => 'nivel 3',
                            4 => 'nivel 4',
                            5 => 'nivel 5'
                        ];
                    @endphp

                    @foreach ($levels as $level => $textLevel)
                        <li>
                            <svg data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3"></path>
                            </svg>
                            @if($actionRequest['NIVAPR'] >= $level)
                                Pendiente de aprobación en el {{ $textLevel }}
                            @else
                                No es necesario la aprobación del {{ $textLevel }}
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="btn-group">
            <button class="btn-approve" wire:click="approve()">
                <svg data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"></path>
                </svg>
                Aprobar
            </button>
            <button wire:click="deny()">
                <svg data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"></path>
                </svg>
                Denegar
            </button>
            <button wire:click="" class="btn-generate">
                <svg data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z">
                    </path>
                </svg>
                Generar Informe
            </button>
        </div>

    </div>

    <div wire:loading>
        <livewire:components.loader />
    </div>
</div>

