<?php

namespace App\Services;

class RequestService
{
    public function getActionData($request, $append = [])
    {
        $user = auth()->user();

        $date = get_date_formatted();

        return array_merge([
            'ACCION' => get_array_value($append, 'TIPINT') * 10000,
            'TRAB' => get_array_value($request, 'TRAB'),
            'FECHA' => $date,
            'NOAUT' => "",
            'AUTOR' => $user->NOM,
            'FECEFE' => $date,
            'COMTRA' => get_array_value($request, 'COMTRA') ? '*' : '',
            'COMRES' => get_array_value($request, 'COMRES') ? '*' : '',
            'DIAS' => get_array_value($request, 'DIAS'),
            'TIPDIA' => get_array_value($request, 'TIPDIA'),
            'DIAFIN' => get_array_value($request, 'DIAFIN'),
            'APLDES' => get_array_value($request, 'APLDES'),
            'DESSAL' => get_array_value($request, 'DESSAL'),
            'DESPRO' => get_array_value($request, 'DESPRO'),
            'SUBCOD' => get_array_value($request, 'SUBCOD'),
            'COMP' => get_array_value($request, 'COMP'),
            'SUC' => get_array_value($request, 'SUC'),
            'DTO' => get_array_value($request, 'DTO'),
            'TODNOM' => get_array_value($request, 'TODNOM'),
            'DIANOM' => get_array_value($request, 'DIANOM'),
            'TODPRO' => get_array_value($request, 'TODPRO'),
            'DIAPRO' => get_array_value($request, 'DIAPRO'),
            'NODIS' => get_array_value($request, 'NODIS'),
            'CODSOL' => get_array_value($request, 'COD'),
            'TIPNOM' => get_array_value($request, 'TIPNOM'),
            'ANOPAG' => get_array_value($request, 'ANOPAG'),
            'MESPAG' => get_array_value($request, 'MESPAG'),
            'PERPAG' => get_array_value($request, 'PERPAG')
        ], $append);
    }
}
