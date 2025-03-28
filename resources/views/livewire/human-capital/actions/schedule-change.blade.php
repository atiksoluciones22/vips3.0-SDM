@extends('livewire.components.action')

@section('content')
<div class="information" style="margin-top: 25px;">
    <div class="header">Cambio de turno</div>
    <div class="lists">
        <div class="list">
            <strong>Origen:</strong>
            <span>{{ get_array_value($originSchedule, 'NOM') }}</span>
        </div>
        <div class="list">
            <strong>Destino:</strong>
            <span>{{ get_array_value($destinationSchedule, 'NOM') }}</span>
        </div>
    </div>
</div>
@endsection
