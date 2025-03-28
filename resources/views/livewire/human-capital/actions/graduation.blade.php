@extends('livewire.components.action')

@section('content')
<div class="information">
    <div class="header header-gray">Salida</div>

    <div class="lists" style="padding:20px; padding-bottom:0">
        <div class="list">
            <strong>Fecha salida:</strong>
            <span>{{ set_date_format($action?->FECSAL) }}</span>
        </div>
        <div class="list">
            <strong>Fecha preaviso:</strong>
            <span>{{ set_date_format($action?->FECCAL) }}</span>
        </div>
        <div class="list">
            <strong>Nueva situación:</strong>
            <span>{{ $action?->DIANOM ?? get_array_value($actionRequest, 'DIANOM') }}</span>
        </div>
        <div class="list">
            <strong>Tipo de salida:</strong>
            <span>{{ $outputType->NOM }}</span>
        </div>
        <div class="list">
            <strong>Razón de salida:</strong>
            <span>{{ $outputType244?->NOM }}</span>
        </div>
    </div>

    <div class="information" style="margin: 20px">
        <div class="header">Prestaciones sociales</div>
        <div class="lists">
            <div class="list">
                <strong>Total:</strong>
                <span></span>
            </div>
        </div>
    </div>
</div>
@endsection
