@extends('livewire.components.action')

@section('content')
<div class="information-x2">
    <div class="information">
        <div class="header">Origen</div>
        <div class="lists">
            <div class="list">
                <strong>Puesto:</strong>
                <span>{{ get_array_value($originWorkstation, 'NOM') }}</span>
            </div>
        </div>
    </div>

    <div class="information">
        <div class="header header-green">Destino</div>
        <div class="lists">
            <div class="list">
                <strong>Puesto:</strong>
                <span>{{ get_array_value($destinationWorkstation, 'NOM') }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
