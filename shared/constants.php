<?php

use Shared\Traits\EnumerableTrait;

enum TypeActions: int
{
    use EnumerableTrait;

    case ENTRY = 1; // Ingreso
    case CHANGE_INDICATOR = 2; // Cambio de indicador
    case WAGE = 3; // Salario
    case TIP = 4; // Propina
    case License = 5; // Licencia
    case VACATION = 6; // Vacación
    case TRANSFER = 7; // Transferencia
    case JOB_CHANGE = 8; // Cambio de posicion
    case SCHEDULE_CHANGE = 9; // Cambio de horario
    case GRADUATION = 10; // Salida
    case REENTRY = 11; // Reingreso
    case ABSENCE = 12; // Ausencias
    case ADMONITION = 13; // Amonestacion
    case SUSPENSION = 14; // Suspesión
}

define("TYPEDAYS", [
    1 => "Dias laborales",
    2 => "Dias Calendario",
    3 => "Semanas",
    4 => "Meses"
]);

define("TYPESALARY", [
    1 => "Mensual",
    2 => "Quincenal",
    3 => "Semanal",
    4 => "Bisemanal",
    5 => "Por horas"
]);
