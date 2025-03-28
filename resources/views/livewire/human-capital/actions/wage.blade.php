@extends('livewire.components.action')

@section('content')
    <div class="information" style="margin-top: 20px; padding:20px">
        <div class="lists">
            <div class="list">
                <strong>Retroactivo a:</strong>
                <span>{{ set_date_format($action?->DIAFIN ?? get_array_value($actionRequest, 'DIAFIN')) }}</span>
            </div>
            <div class="list">
                <strong>Aplicar desde:</strong>
                <span>{{ set_date_format($action?->FECEFE ?? get_array_value($actionRequest, 'FECEFE')) }}</span>
            </div>

            <div class="list">
                <strong>Razón del cambio de salario:</strong>
                <span></span>
            </div>
            <div class="list">
                <strong>Acción:</strong>
                <span>{{ $action?->TURORI ?? get_array_value($actionRequest, 'TURORI') }}</span>
            </div>
        </div>

        <div class="information-x2">
            <div class="information">
                <div class="header">Dato actual</div>
                <div class="lists">
                    <div class="list">
                        <strong>Importe a subir:</strong>
                        <span>{{ $action?->IMPDIF ?? get_array_value($actionRequest, 'IMPDIF') }}</span>
                    </div>
                    <div class="list">
                        <strong>Importe:</strong>
                        <span>{{ $action?->PROORI ?? get_array_value($actionRequest, 'PROORI') }}</span>
                    </div>
                    <div class="list">
                        <strong>Divisa:</strong>
                        <span>{{ $action?->DIVORI ?? get_array_value($actionRequest, 'DIVORI') }}</span>
                    </div>
                </div>
            </div>

            <div class="information">
                <div class="header header-green">Nuevo dato</div>
                <div class="lists">
                    <div class="list">
                        <strong>Importe:</strong>
                        <span>{{ $action?->PRONUE ?? get_array_value($actionRequest, 'PRONUE') }}</span>
                    </div>
                    <div class="list">
                        <strong>Divisa:</strong>
                        <span>{{ $action?->DIVNUE ?? get_array_value($actionRequest, 'DIVNUE') }}</span>
                    </div>
                    <div class="list">
                        <strong>Tipo de salario:</strong>
                        <span>{{ get_array_value(TYPESALARY, $action?->TIPDIA ?? get_array_value($actionRequest, 'TIPDIA')) }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
