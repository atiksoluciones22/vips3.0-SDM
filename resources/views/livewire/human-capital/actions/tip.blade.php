@extends('livewire.components.action')

@section('content')
<div class="information-x2">
    <div class="information">
        <div class="header">Propina actual</div>
        <div class="lists">
            <div class="list">
                <strong>Punto:</strong>
                <span>{{ $action?->PROORI ?? get_array_value($actionRequest, 'PROORI') }}</span>
            </div>
            <div class="list">
                <strong>Divisa:</strong>
                <span>{{ $action?->DIVORI ?? get_array_value($actionRequest, 'DIVORI') }}</span>
            </div>
        </div>
    </div>

    <div class="information">
        <div class="header header-green">Nueva propina</div>
        <div class="lists">
            <div class="list">
                <strong>Punto:</strong>
                <span>{{ $action?->PRONUE ?? get_array_value($actionRequest, 'PRONUE') }}</span>
            </div>
            <div class="list">
                <strong>Divisa:</strong>
                <span>{{ $action?->DIVNUE ?? get_array_value($actionRequest, 'DIVNUE') }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
