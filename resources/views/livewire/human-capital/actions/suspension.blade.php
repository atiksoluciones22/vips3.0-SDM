@extends('livewire.components.action')

@section('content')
<div class="information" style="margin-top: 20px; padding:20px">
    <div class="lists">
        <div class="list">
            <strong>Causa:</strong>
            <span>{{ $action?->TEXBEN ?? get_array_value($actionRequest, 'TEXBEN') }}</span>
        </div>

        <div class="list">
            <strong>Fecha inicio:</strong>
            <span>{{ set_date_format($action?->FECEFE ?? get_array_value($actionRequest, 'FECEFE')) }}</span>
        </div>
        <div class="list">
            <strong>Días:</strong>
            <span>{{ $action?->DIAS ?? get_array_value($actionRequest, 'DIAS') }}</span>
        </div>
        <div class="list">
            <strong>Tipo de días:</strong>
            <span>{{ get_array_value(TYPEDAYS, $action?->TIPDIA ?? get_array_value($actionRequest, 'TIPDIA')) }}</span>
        </div>
        <div class="list">
            <strong>Día de final:</strong>
            <span>{{ set_date_format($action?->DIAFIN ?? get_array_value($actionRequest, 'DIAFIN')) }}</span>
        </div>
    </div>

    <div class="information">
        <div class="header header-gray">Descuentos</div>
        <div class="lists" style="padding:20px; padding-bottom:0">
            <div class="list">
                <strong>Aplicar descuento:</strong>
                <span>@include('includes.check-or-not', ['value' => ($action?->APLDES ?? get_array_value($actionRequest, 'APLDES'))])</span>
            </div>
        </div>

        <div class="information-x2" style="padding:20px; padding-top:0;">
            <div class="information">
                <div class="header">En salarios</div>
                <div class="lists">
                    <div class="list">
                        <strong>Todos los días:</strong>
                        <span>@include('includes.check-or-not', ['value' => ($action?->TODNOM ?? get_array_value($actionRequest, 'TODNOM'))])</span>
                    </div>
                    <div class="list">
                        <strong>Días:</strong>
                        <span>{{ $action?->DIANOM ?? get_array_value($actionRequest, 'DIANOM') }}</span>
                    </div>
                </div>
            </div>

            <div class="information">
                <div class="header header-green">En propinas</div>
                <div class="lists">
                    <div class="list">
                        <strong>Todos los días:</strong>
                        <span>@include('includes.check-or-not', ['value' => ($action?->TODPRO ?? get_array_value($actionRequest, 'TODPRO'))])</span>
                    </div>
                    <div class="list">
                        <strong>Días:</strong>
                        <span>{{ $action?->DIAPRO ?? get_array_value($actionRequest, 'DIAPRO') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div style="padding:20px; padding-top:0;">
            <div class="information">
                <div class="header header-green">Aplicar en las nóminas</div>
                <div class="lists">
                    <div class="list">
                        <strong>Las que corresponda:</strong>
                        <span>@include('includes.check-or-not', ['value' => ($action?->NODIS ?? get_array_value($actionRequest, 'NODIS'))])</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
