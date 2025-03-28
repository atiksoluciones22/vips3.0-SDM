<?php

namespace App\Services;


class DBService
{
    /**
     * Transfiere datos de una tabla de origen a una tabla de destino y opcionalmente elimina los registros de la tabla de origen.
     *
     * @param string $originModel El nombre completo de la clase del modelo de origen.
     * @param string $destinationModel El nombre completo de la clase del modelo de destino.
     * @param array $originWheres Un arreglo de arreglos que especifica las condiciones de selección de registros en la tabla de origen. Cada subarreglo debe contener tres elementos: el nombre de la columna, el operador de comparación y el valor de comparación.
     * @param array $destinationWheres
     * @param array $columns Un arreglo de nombres de columnas que se deben seleccionar de la tabla de origen. Por defecto, se seleccionan todas las columnas ('*').
     * @return void
     */
    public function transferData($originModel, $destinationModel, $originWheres = [], $destinationWheres = [], $columns = ["*"], $changeArrayKeys = [], $deleteOrigin = true)
    {
        $originQuery = $originModel::query()->select($columns);

        foreach ($originWheres as $key => $value) {
            $originQuery->where($key, $value);
        }

        $data = $originQuery->get();

        foreach ($data as $row) {
            $destinationQuery = $destinationModel::query();

            foreach ($destinationWheres as $key => $value) {
                $destinationQuery->where($key, $row->$value);
            }

            $destinationModelInstance = $destinationQuery->firstOrNew([]);

            $row = $row->toArray();

            $this->changeArrayKeys($row, $changeArrayKeys);

            $destinationModelInstance->fill($row)->save();
        }

        if (!empty($originWheres) && $deleteOrigin) {
            $originModel::where($originWheres)->delete();
        }
    }

    private function changeArrayKeys(&$array, $keyMap) {
        foreach ($keyMap as $oldKey => $newKey) {
            if (array_key_exists($oldKey, $array)) {
                // Guardar el valor de la clave antigua
                $value = $array[$oldKey];

                // Eliminar la clave antigua
                unset($array[$oldKey]);

                // Añadir el valor con la nueva clave
                $array[$newKey] = $value;
            }
        }
    }

    /**
     * Inserta datos en un modelo, generando automáticamente valores incrementales para columnas especificadas.
     *
     * @param string $model El modelo en el que insertar los datos.
     * @param array $data Los datos a insertar en el modelo.
     * @param array $wheres Los criterios de búsqueda para seleccionar los registros que se utilizarán para generar los valores incrementales.
     * @param array $increments Las columnas para las que se generarán valores incrementales.
     * @param array $append Los datos adicionales que se insertarán en el modelo.
     * @return void
     */
    public function insert($model, $array, $wheres = [], $increments = ['COD'], $append = [], $returnData = false)
    {
        foreach ($array as $data) {
            $incrementValues = [];

            foreach ($increments as $column) {
                $incrementValues[$column] = $model::query()
                    ->where($wheres)
                    ->max($column) + 1;
            }

            $data = array_merge($data, $incrementValues, $append);

            if($returnData) return $model::create($data);

            $model::insert($data);
        }
    }

    public function getEntityData($model, array $conditions, array $selectFields = ['*'])
    {
        return $model::where($conditions)->select($selectFields)->first()?->toArray();
    }

    public function getOriginData($request)
    {
        $data = [
            'COMP' => get_array_value($request, 'employee.COMP'),
            'SUC'  => get_array_value($request, 'employee.SUC'),
            'DTO'  => get_array_value($request, 'employee.DTO'),
            'SECC' => get_array_value($request, 'employee.SECC'),
            'UNI'  => get_array_value($request, 'employee.UNI')
        ];

        return [
            'company' => $this->getEntityData(\App\Models\Company::class, ['COD' => $data['COMP']], ['COD', 'NOM', 'NOMCOM']),
            'branch' => $this->getEntityData(\App\Models\Branch::class, ['COD' => $data['SUC'], 'COMP' => $data['COMP']]),
            'department' => $this->getEntityData(\App\Models\Department::class, ['COD' => $data['DTO'], 'SUC' => $data['SUC'], 'COMP' => $data['COMP']]),
            'section' => $this->getEntityData(\App\Models\Section::class, ['COD' => $data['SECC'], 'SUC' => $data['SUC'], 'COMP' => $data['COMP'], 'DTO' => $data['DTO']]),
            'unit' => $this->getEntityData(\App\Models\SectionUnit::class, ['COD' => $data['UNI'], 'SEC' => $data['SECC'], 'SUC' => $data['SUC'], 'COMP' => $data['COMP'], 'DTO' => $data['DTO']])
        ];
    }

    public function getDestinationData($request)
    {
        $data = [
            'COMP' => get_array_value($request, 'COMP'),
            'SUC'  => get_array_value($request, 'SUC'),
            'DTO'  => get_array_value($request, 'DTO'),
            'SECC' => get_array_value($request, 'SECC'),
            'UNI'  => get_array_value($request, 'UNI')
        ];

        return [
            'company' => $this->getEntityData(\App\Models\Company::class, ['COD' => $data['COMP']], ['COD', 'NOM', 'NOMCOM']),
            'branch' => $this->getEntityData(\App\Models\Branch::class, ['COD' => $data['SUC'], 'COMP' => $data['COMP']]),
            'department' => $this->getEntityData(\App\Models\Department::class, ['COD' => $data['DTO'], 'SUC' => $data['SUC'], 'COMP' => $data['COMP']]),
            'section' => $this->getEntityData(\App\Models\Section::class, ['COD' => $data['SECC'], 'SUC' => $data['SUC'], 'COMP' => $data['COMP'], 'DTO' => $data['DTO']]),
            'unit' => $this->getEntityData(\App\Models\SectionUnit::class, ['COD' => $data['UNI'], 'SEC' => $data['SECC'], 'SUC' => $data['SUC'], 'COMP' => $data['COMP'], 'DTO' => $data['DTO']])
        ];
    }
}
