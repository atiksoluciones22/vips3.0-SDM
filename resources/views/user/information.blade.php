@extends('layouts.app')

@section('content')
    <div class="card card-default">
        <div class="card-header card-header-border-bottom">
            <h2>Datos personales</h2>
        </div>

        <div class="row no-gutters">
            <div class="col-lg-6 col-xl-6">
                <div class="profile-content-left profile-left-spacing pb-3 px-3 px-xl-5">
                    <div class="contact-info pt-4">
                        <p class="text-dark font-weight-medium mb-2">Cedula</p>
                        <p>{{ $user->CEDULA }}</p>
                        <p class="text-dark font-weight-medium pt-4 mb-2">Nacionalidad</p>
                        <p></p>
                        <p class="text-dark font-weight-medium pt-4 mb-2">Provincia</p>
                        <p></p>
                        <p class="text-dark font-weight-medium pt-4 mb-2">Dirección</p>
                        <p></p>
                        <p class="text-dark font-weight-medium pt-4 mb-2">Puesto</p>
                        <p>{{ $user->workstation->NOM }}</p>
                        <p class="text-dark font-weight-medium pt-4 mb-2">Compañía</p>
                        <p>{{ $user->company->NOM }}</p>
                        <p class="text-dark font-weight-medium pt-4 mb-2">Sucursal</p>
                        <p>{{ $user->branch->NOM }}</p>
                        <p class="text-dark font-weight-medium pt-4 mb-2">Departamento</p>
                        <p>{{ $user->department->NOM }}</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-xl-6">
                <div class="profile-content-left profile-left-spacing pb-3 px-3 px-xl-5">
                    <div class="contact-info pt-4">
                        <p class="text-dark font-weight-medium mb-2">Tipo de empleado</p>
                        <p></p>
                        <p class="text-dark font-weight-medium pt-4 mb-2">Nivel</p>
                        <p>{{ $user->NIVEL }}</p>
                        <p class="text-dark font-weight-medium pt-4 mb-2">Banda salarial</p>
                        <p>{{ $user->salary_band->NOM }}</p>
                        <p class="text-dark font-weight-medium pt-4 mb-2">Turno</p>
                        <p></p>
                        <p class="text-dark font-weight-medium pt-4 mb-2">Banco</p>
                        <p></p>
                        <p class="text-dark font-weight-medium pt-4 mb-2">Divisa</p>
                        <p></p>
                        <p class="text-dark font-weight-medium pt-4 mb-2">Unidad</p>
                        <p></p>
                        <p class="text-dark font-weight-medium pt-4 mb-2">Compañía de ARS</p>
                        <p>{{ $user->CIAARS }}</p>
                        <p class="text-dark font-weight-medium pt-4 mb-2">Compañía de AFP</p>
                        <p>{{ $user->CIAAFP }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
