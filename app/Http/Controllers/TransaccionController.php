<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transaccion;
use App\Categoria;
use Auth;
use DB;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\Transaccion\TransaccionResource;
use App\Http\Resources\Transaccion\TransaccionesResource;

class TransaccionController extends Controller
{
    protected $user;
 
    public function __construct()
    {
        $this->user = Auth::Guard('api')->user();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $transaccion = Transaccion::select('id', 'asunto', 'monto', 'tipo', 'categoria_id', 'fecha')->with('categoria')->where('user_id', $this->user->id);
                                
        Log::info('Entre en index');
        if ($request->filled('categoria_id')) {            
            $id = $request->input('categoria_id');
            $transaccion = $transaccion->where('categoria_id', $id);
        }
        
        if ($request->filled('tipo')) {
            $tipo = $request->input('tipo');
            $transaccion = $transaccion->where('tipo', $tipo);
        }
        
        if ($request->filled('created_at_desde') && !$request->filled('created_at_hasta')) {
            $fecha_desde = date_create($request->input('created_at_desde'));
            $fecha_desde = date_format($fecha_desde, 'Y-m-d');
                
            $transaccion = $transaccion->whereDate('fecha', '>=', $fecha_desde);

        } else if (!$request->filled('created_at_desde') 
            && $request->filled('created_at_hasta')) {

            $fecha_hasta = date_create($request->input('created_at_hasta'));
            $fecha_hasta = date_format($fecha_hasta, 'Y-m-d');
                
            $fecha_hasta = $request->input('created_at_hasta');
            $transaccion = $transaccion->whereDate('fecha', '<=',$fecha_hasta);

        } else if ($request->filled('created_at_desde') && $request->filled('created_at_hasta')) {

            $fecha_desde = date_create($request->input('created_at_desde'));
            $fecha_desde = date_format($fecha_desde, 'Y-m-d');

            $fecha_hasta = date_create($request->input('created_at_hasta'));
            $fecha_hasta = date_format($fecha_hasta, 'Y-m-d');


            $transaccion = $transaccion->whereDate('fecha', '>=', $fecha_desde);
            $transaccion = $transaccion->whereDate('fecha', '<=',$fecha_hasta);
            // $transaccion = $transaccion->whereBetween('created_at', [$fecha_desde, $fecha_hasta]);

        } else {
            Log::info('Sin fechas sin filtro');            
        }
        
        $transaccion = $transaccion->orderBy('id', 'desc')->paginate(5);
      
        return new TransaccionesResource($transaccion);
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
            'asunto'       => 'required|string|max:50',
            'categoria_id' => 'required|integer',
            'monto'        => 'required|numeric',
            'fecha'        => 'required|date',
            'tipo'         => 'required|integer'

        ]);
        DB::transaction(function () use ($request) {
            $cat_id = $request->input('categoria_id');
            $categoria = Categoria::findOrFail($cat_id);

            if (!$categoria) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lo sentimos, la categoria con el id ' . $cat_id . ' no existe'
                ], 400);
            }
            $fecha = date_create($request->input('fecha'));
            $fecha = date_format($fecha, 'Y-m-d');

            $transaccion = new Transaccion;
            $transaccion->asunto = $request->input('asunto');
            $transaccion->categoria_id = $request->input('categoria_id');
            $transaccion->monto = $request->input('monto');
            $transaccion->fecha = $fecha;
            $transaccion->tipo = $request->input('tipo');      

            if ($this->user->transacciones()->save($transaccion)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Transaccion creada satisfactoriamente',
                    'transaccion' => new TransaccionResource($transaccion)
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'La transaccion no pudo ser creada'
                ], 500);
            }
        });                    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
            'asunto'       => 'required|string|max:50',
            'categoria_id' => 'required|integer',
            'monto'        => 'required|numeric',
            'fecha'        => 'required|date',
            'tipo'         => 'required|integer'

        ]);
        $categoria_id = $request->input('categoria_id');
        $categoria = Categoria::findOrFail($categoria_id);

        if (!$categoria) {
            return response()->json([
                'success' => false,
                'message' => 'Lo sentimos, la categoria con el id ' . $categoria_id . ' no existe'
            ], 400);
        }
        $fecha = date_create($request->input('fecha'));
        $fecha = date_format($fecha, 'Y-m-d');

        $transaccion = Transaccion::findOrFail($id);
        if (!$transaccion) {
            return response()->json([
                'success' => false,
                'message' => 'Lo sentimos, la transacción con el id ' . $id . ' no existe'
            ], 400);
        }

        if ($request->input('asunto') != $transaccion->asunto) {
            $transaccion->asunto = $request->input('asunto');
        }
        if ($categoria_id != $transaccion->categoria_id) {
            $transaccion->categoria_id = $categoria_id;
        }

        if ($request->input('monto') != $transaccion->monto) {
            $transaccion->monto = $request->input('monto');
        }

        if ($request->input('tipo') != $transaccion->tipo) {
            $transaccion->tipo = $request->input('tipo');
        }

        if ($fecha != $transaccion->fecha) {
            $transaccion->fecha = $fecha;
        }  

        if ($transaccion->update()) {
            return response()->json([
                'success' => true,
                'message' => 'Transaccion actualizada satisfactoriamente'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Lo sentimos, la transacción no pudo ser actualizada'
            ], 500);
        } 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $transaccion = Transaccion::find($id);
        if ($transaccion->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Transaccion eliminada satisfactoriamente'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'La transaccion no pudo ser eliminada'
            ], 500);
        }
    }
}
