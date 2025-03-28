<?php

namespace App;

use Illuminate\Support\Facades\{DB, Auth, Cache, Config};

class DatabaseConnectionManager
{
    public function setConnectionConfig($connection)
    {
        try {
            Config::set('database.connections.sqlsrv.host', $connection->Server);
            Config::set('database.connections.sqlsrv.port', $connection->Port);
            Config::set('database.connections.sqlsrv.database', $connection->BBDD);
            Config::set('database.connections.sqlsrv.username', $connection->User_name);
            Config::set('database.connections.sqlsrv.password', $connection->Password);
            Config::set('database.connections.sqlsrv.prefix', strtoupper(substr($connection->BBDD, -3)));

            Config::set('database.connections.sqlsrv_sdm.host', $connection->Server);
            Config::set('database.connections.sqlsrv_sdm.port', $connection->Port);
            Config::set('database.connections.sqlsrv_sdm.database', $connection->SDMSDM);
            Config::set('database.connections.sqlsrv_sdm.username', $connection->User_name);
            Config::set('database.connections.sqlsrv_sdm.password', $connection->Password);
            Config::set('database.connections.sqlsrv_sdm.prefix', 'SDM');

            DB::purge('sqlsrv');
            DB::reconnect('sqlsrv');
            DB::connection('sqlsrv')->getPDO();
            DB::connection('sqlsrv')->getDatabaseName();

            DB::purge('sqlsrv_sdm');
            DB::reconnect('sqlsrv_sdm');
            DB::connection('sqlsrv_sdm')->getPDO();
            DB::connection('sqlsrv_sdm')->getDatabaseName();
            return true;
        } catch (\Exception $e) {
            Auth::logout();
            Cache::forget('company');
            return redirect('/login')->with('error', 'Error al conectar con la base de datos.')->withInput();
        }
    }
}
