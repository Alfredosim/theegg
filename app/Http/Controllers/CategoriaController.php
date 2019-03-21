<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Categoria;
use App\Transaccion;
use App\Http\Resources\Categoria\CategoriaResource;
use App\Http\Resources\Categoria\CategoriasResource;
use Illuminate\Support\Facades\Log;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categoria = Categoria::select('id', 'nombre', 'descripcion', 'created_at')
                            ->withCount('transacciones');

        if ($request->filled('nombre')) {            
            $nombre = $request->input('nombre');
            $categoria = $categoria->where('nombre', 'like', $nombre.'%');
        }
        
        if ($request->filled('descripcion')) {
            $descripcion = $request->input('descripcion');
            $categoria = $categoria->where('descripcion', 'like', $descripcion.'%');
        }
        
        if ($request->filled('created_at_desde') && !$request->filled('created_at_hasta')) {
            $fecha_desde = date_create($request->input('created_at_desde'));
            $fecha_desde = date_format($fecha_desde, 'Y-m-d');
                
            $categoria = $categoria->whereDate('created_at', '>=', $fecha_desde);

        } else if (!$request->filled('created_at_desde') 
            && $request->filled('created_at_hasta')) {

            $fecha_hasta = date_create($request->input('created_at_hasta'));
            $fecha_hasta = date_format($fecha_hasta, 'Y-m-d');
                
            $fecha_hasta = $request->input('created_at_hasta');
            $categoria = $categoria->whereDate('created_at', '<=',$fecha_hasta);

        } else if ($request->filled('created_at_desde') && $request->filled('created_at_hasta')) {

            $fecha_desde = date_create($request->input('created_at_desde'));
            $fecha_desde = date_format($fecha_desde, 'Y-m-d');

            $fecha_hasta = date_create($request->input('created_at_hasta'));
            $fecha_hasta = date_format($fecha_hasta, 'Y-m-d');

            $categoria = $categoria->whereDate('created_at', '>=', $fecha_desde);
            $categoria = $categoria->whereDate('created_at', '<=',$fecha_hasta);

        } else {
            Log::info('Sin fechas sin filtro');            
        }
        
        $categoria = $categoria->orderBy('id', 'desc')->paginate(5);
      
        return new CategoriasResource($categoria);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        $categoria = Categoria::select('id', 'nombre')->orderBy('id', 'desc')->get();
        
        return new CategoriasResource($categoria);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $vali = $request->validate([
            'nombre'  => 'required|string|max:50',
            'descripcion' => 'required|string|max:500'  
        ]);

        $categoria = new Categoria;

        $categoria->nombre = $request->input('nombre');
        $categoria->descripcion = $request->input('descripcion');      

        if ($categoria->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Categoria registrada satisfactoriamente'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'La categoria no pudo ser creada'
            ], 500);
        }
                   
 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $vali = $request->validate([
            'nombre'  => 'required|string|max:50',
            'descripcion' => 'required|string|max:500'  
        ]);

        $categoria = Categoria::findOrFail($id);

        if (!$categoria) {
            return response()->json([
                'success' => false,
                'message' => 'Lo sentimos, la categoria con el id ' . $id . ' no pudo ser encontrada'
            ], 400);
        }

        if ($request->input('nombre') != $categoria->nombre) {
            $categoria->nombre = $request->input('nombre');
        }

        if ($request->input('descripcion') != $categoria->descripcion) {
            $categoria->descripcion = $request->input('descripcion');
        }
        
        if ($categoria->update()) {
            return response()->json([
                'success' => true,
                'message' => 'Categoria actualizada satisfactoriamente'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Lo sentimos, la categoria no pudo ser actualizada'
            ], 500);
        }
        
    }
    
}
