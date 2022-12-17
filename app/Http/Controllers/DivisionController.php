<?php

namespace App\Http\Controllers;

use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DivisionController extends Controller
{

    /**
     * OCRUZADO - FUNCIÓN PARA CREAR Y ACTUALIZAR DIVISIÓN
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function api_store(Request $request)
    {
        $request->validate([
            'divi_IdDivision' => 'required|integer',
            'disu_IdDivisionSuperior' => 'required|integer',
            'divi_Nombre' => "required|string|min:3|max:45|unique:division,divi_Nombre,$request->divi_IdDivision,divi_IdDivision",

            'divi_Nivel' => 'required|integer|min:1',
            'divi_Colaborador_Cantidad' => 'required|integer|min:1',

        ], [
            'divi_IdDivision.required' => 'IDENTIFICADOR VALOR REQUERIDO',
            'divi_IdDivision.integer' => 'IDENTIFICADOR FORMATO INCORRECTO',

            'disu_IdDivisionSuperior.required' => 'DIVISIÓN SUPERIOR IDENTIFICADOR VALOR REQUERIDO',
            'disu_IdDivisionSuperior.integer' => 'DIVISIÓN SUPERIOR FORMATO INCORRECTO',

            'divi_Nombre.required' => 'DEBE INGRESAR UN NOMBRE',
            'divi_Nombre.string' => 'EL NOMBRE INGRESADO DEBE SER UN STRING',
            'divi_Nombre.min' => 'EL NOMBRE INGRESADO DEBE TENER AL MENOS 3 CARACTERES',
            'divi_Nombre.max' => 'EL NOMBRE INGRESADO DEBE TENER MAXIMO 45 CARACTERES',
            'divi_Nombre.unique' => 'EL NOMBRE DE LA DIVISIÓN DEBE SER ÚNICO',

            'divi_Nivel.required' => 'DEBE INGRESAR EL NIVEL',
            'divi_Nivel.integer' => 'NIVEL - FORMATO INCORRECTO',
            'divi_Nivel.min' => 'EL NIVEL DEBE SER UN NÚMERO ENTERO POSITIVO',

            'divi_Colaborador_Cantidad.required' => 'DEBE INGRESAR LA CANTIDAD DE COLABORADORES',
            'divi_Colaborador_Cantidad.integer' => ' CANTIDAD DE COLABORADORES - FORMATO INCORRECTO',
            'divi_Colaborador_Cantidad.min' => ' CANTIDAD DE COLABORADORES DEBE SER UN NÚMERO ENTERO POSITIVO',
        ]);

        $data = Division::find($request->divi_IdDivision);

        if (!$data) {

            $new = Division::create([
                'disu_IdDivisionSuperior' => $request->disu_IdDivisionSuperior,

                'divi_Nombre' => $request->divi_Nombre,
                'divi_Nivel' => $request->divi_Nivel,
                'divi_Colaborador_Cantidad' => $request->divi_Colaborador_Cantidad,
                'divi_Embajador_Nombre' => $request->divi_Embajador_Nombre ? $request->divi_Embajador_Nombre : ''
            ]);

            return response()->json([
                'success' => true,
                'id' => $new->divi_IdDivision,
                'message' => 'REGISTRO CREADO CORRECTAMENTE'
            ]);

        } else {

            $data->disu_IdDivisionSuperior = $request->disu_IdDivisionSuperior;

            $data->divi_Nombre = $request->divi_Nombre;
            $data->divi_Nivel = $request->divi_Nivel;
            $data->divi_Colaborador_Cantidad = $request->divi_Colaborador_Cantidad;
            $data->divi_Embajador_Nombre = $request->divi_Embajador_Nombre ? $request->divi_Embajador_Nombre : '';

            $data->save();

            return response()->json([
                'success' => true,
                'message' => 'REGISTRO ACTUALIZADO CORRECTAMENTE'
            ]);
        }

    }

    /**
     * OCRUZADO - FUNCIÓN PARA LISTAR TODAS LAS DIVISIONES
     * @param Request $request
     * @return \Illuminate\Support\Collection
     */
    public function api_list(Request $request)
    {
        $search = $request->p_search;
        $columnas = $request->p_columna;


        $list_sub_divisiones = DB::table('division as d')
            ->select('d.disu_IdDivisionSuperior as x_divi_IdDivision', DB::raw('count(d.disu_IdDivisionSuperior) as x_divi_Sub_Divisiones'))
            ->groupBy('d.disu_IdDivisionSuperior');

        $list_query = DB::table('division as d')
            ->leftJoin('division as ds', 'd.disu_IdDivisionSuperior', '=', 'ds.divi_IdDivision')
            ->leftJoinSub($list_sub_divisiones, 'ds_list', function ($join) {
                $join->on('d.divi_IdDivision', '=', 'ds_list.x_divi_IdDivision');
            })->select('d.divi_IdDivision',
                'd.divi_Nombre',
                'd.divi_Nivel',
                'd.divi_Colaborador_Cantidad',
                'd.divi_Embajador_Nombre',

                DB::raw("IFNULL(ds_list.x_divi_Sub_Divisiones, 0) as divi_Sub_Divisiones"),
                DB::raw("IFNULL(ds.divi_IdDivision, 0) as disu_IdDivisionSuperior"),
                DB::raw("IFNULL(ds.divi_Nombre, '- : -') as disu_Nombre"),
            );


        if ($columnas) {
            foreach ($columnas as $columna) {
                $list_query->where($columna, 'like', "%$search%");
            }
        } else {
            $list_query
                ->where('d.divi_Nombre', 'like', "%$search%")
                ->orWhere('ds.divi_Nombre', 'like', "%$search%")
                ->orWhere('d.divi_Colaborador_Cantidad', 'like', "%$search%")
                ->orWhere('d.divi_Nivel', 'like', "%$search%")
                ->orWhere('d.divi_Embajador_Nombre', 'like', "%$search%");
        }

//        dd($list_query->toSql());

        $list = $list_query->get();

        return $list;
    }

    /**
     * OCRUZADO - FUNCIÓN PARA ELIMINAR UNA DIVISIÓN DE LA BASE DE DATOS
     * @param Division $item
     * @return \Illuminate\Http\JsonResponse
     */
    public function api_remove(Division $item)
    {
        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'registro eliminado correctamente'
        ]);
    }

    /**
     * OCRUZADO - FUNCIÓN PARA CONSULTAR UNA DIVISIÓN DE LA BASE DE DATOS
     * @param Division $item
     * @return \Illuminate\Http\JsonResponse
     */
    public function api_get(Division $item)
    {
        return $item;
    }


    /**
     * OCRUZADO - FUNCIÓN PARA LISTAR SUBDIVISIONES DE UNA DIVISIÓN
     * @param Division $item
     * @return \Illuminate\Http\JsonResponse
     */
    public function api_get_sub(Division $item)
    {
        $list = Division::where('disu_IdDivisionSuperior', '=', $item->divi_IdDivision)
            ->get();

        return $list;
    }
}
