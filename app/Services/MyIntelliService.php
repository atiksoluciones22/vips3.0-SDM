<?php

namespace App\Services;

class MyIntelliService
{
    public function personas() {
        $personas = file_get_contents('https://api.admin.myintelli.net/v1/integrators/7d4fd9b8-e37d-4705-a450-f92aa83d8c0d/persons/');
        return json_decode($personas);
    }
}
