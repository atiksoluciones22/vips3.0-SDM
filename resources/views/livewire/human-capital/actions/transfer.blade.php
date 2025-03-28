@extends('livewire.components.action')

@section('content')
    <div class="information-x2">
        <div class="information">
            <div class="header">Origen</div>
            <div class="lists">
                <div class="list">
                    <strong>Compañía:</strong>
                    <span>{{ get_array_value($origin, 'company.NOMCOM') }}</span>
                </div>
                <div class="list">
                    <strong>Sucursal:</strong>
                    <span>{{ get_array_value($origin, 'branch.NOM') }}</span>
                </div>
                <div class="list">
                    <strong>Departamento:</strong>
                    <span>{{ get_array_value($origin, 'department.NOM') }}</span>
                </div>
                <div class="list">
                    <strong>Sección:</strong>
                    <span>{{ get_array_value($origin, 'section.NOM') }}</span>
                </div>
                <div class="list">
                    <strong>Unidad:</strong>
                    <span>{{ get_array_value($origin, 'unit.NOM') }}</span>
                </div>
            </div>
        </div>

        <div class="information">
            <div class="header header-green">Destino</div>
            <div class="lists">
                <div class="list">
                    <strong>Compañía:</strong>
                    <span>{{ get_array_value($destination, 'company.NOMCOM') }}</span>
                </div>
                <div class="list">
                    <strong>Sucursal:</strong>
                    <span>{{ get_array_value($destination, 'branch.NOM') }}</span>
                </div>
                <div class="list">
                    <strong>Departamento:</strong>
                    <span>{{ get_array_value($destination, 'department.NOM') }}</span>
                </div>
                <div class="list">
                    <strong>Sección:</strong>
                    <span>{{ get_array_value($destination, 'section.NOM') }}</span>
                </div>
                <div class="list">
                    <strong>Unidad:</strong>
                    <span>{{ get_array_value($destination, 'unit.NOM') }}</span>
                </div>
            </div>
        </div>
    </div>
@endsection
