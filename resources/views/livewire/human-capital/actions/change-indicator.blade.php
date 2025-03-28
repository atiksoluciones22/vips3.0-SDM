@extends('livewire.components.action')

@section('content')
<div class="lists" style="margin-top: 20px;">
    <div class="list">
        <strong>Tipo de cambio:</strong>
        <span>{{ get_array_value($actionRequest, 'TEXBEN') }}</span>
    </div>
</div>
@endsection
