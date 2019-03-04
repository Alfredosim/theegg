<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cliente;
use App\Http\Resources\Cliente\ClienteResource;
use App\Http\Resources\Cliente\ClientesResource;
use Illuminate\Support\Facades\Log;


class ClienteController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Log::info('Entre en index');
        $clientes = Cliente::select('id', 'nombre', 'apellido', 'cedula',
                                    'telefono', 'direccion', 'active', 'created_at',
                                    'updated_at');

        if ($request->filled('nombre')) {
            
            Log::info('Entre en nombre');
            $nombre = $request->input('nombre');
            $clientes = $clientes->where('nombre', 'like', $nombre.'%');
        }
        
        if ($request->filled('apellido')) {

            Log::info('Entre en apellido');
            $apellido = $request->input('apellido');
            $clientes = $clientes->where('apellido', 'like', $apellido.'%');
        } 
        
        if ($request->filled('cedula')) {

            Log::info('Entre en cedula'); 
            $cedula = $request->input('cedula');
            $clientes = $clientes->where('cedula', 'like', $cedula.'%');
        } 
        
        if ($request->filled('telefono')) {

            Log::info('Entre en telefono');
            $telefono = $request->input('telefono');
            $clientes = $clientes->where('telefono', 'like', '%'.$telefono.'%');
        } 
        
        if ($request->filled('direccion')) {

            Log::info('Entre en direccion');  
            $direccion = $request->input('direccion');
            $clientes = $clientes->where('direccion', 'like', $direccion.'%');
        } 
        
        if ($request->input('active') == true) {

            Log::info('Entre en active');
            $clientes = $clientes->where('active', '=', 1);
        } 
        
        if ($request->filled('created_at_desde') && !$request->filled('created_at_hasta')) {

            Log::info('Entre en desde: ' . $request->input('created_at_desde'));
            $fecha_desde = date_create($request->input('created_at_desde'));
            $fecha_desde = date_format($fecha_desde, 'Y-m-d');
                
            $clientes = $clientes->whereDate('created_at', '>=', $fecha_desde);

        } else if (!$request->filled('created_at_desde') 
            && $request->filled('created_at_hasta')) {

            Log::info('Entre en hasta: ' . $request->input('created_at_hasta'));
            $fecha_hasta = date_create($request->input('created_at_hasta'));
            $fecha_hasta = date_format($fecha_hasta, 'Y-m-d');

                
            $fecha_hasta = $request->input('created_at_hasta');
            $clientes = $clientes->whereDate('created_at', '<=',$fecha_hasta);

        } else if ($request->filled('created_at_desde') && $request->filled('created_at_hasta')) {

            Log::info('Entre en ambas fechas'); 
            $fecha_desde = date_create($request->input('created_at_desde'));
            $fecha_desde = date_format($fecha_desde, 'Y-m-d');

            $fecha_hasta = date_create($request->input('created_at_hasta'));
            $fecha_hasta = date_format($fecha_hasta, 'Y-m-d');


            $clientes = $clientes->whereDate('created_at', '>=', $fecha_desde);
            $clientes = $clientes->whereDate('created_at', '<=',$fecha_hasta);
            // $clientes = $clientes->whereBetween('created_at', [$fecha_desde, $fecha_hasta]);

        } else {
            Log::info('Sin fechas sin filtro');            
        }
        
        $clientes = $clientes->orderBy('id', 'desc')->paginate(8);
      
        return new ClientesResource($clientes);
    }

    /**
     * Obtiene las estadisticas de los clientes.
     *
     * @return \Illuminate\Http\Response
     */
    public function estadisticas()
    {
        $total = Cliente::count();
        $activos = Cliente::where('active', 1)->count();
        $inactivos = $total - $activos;
                            
        $collection = collect(['total' => $total,
                                'activos' => $activos,
                                'inactivos'   => $inactivos
                            ]);
        
        return $collection->toJson();
       
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
            'nombre'       => 'required|string|max:30',
            'apellido'     => 'required|string|max:30',
            'cedula'       => 'required|unique:clientes|max:20',
            'telefono'     => 'required|string|max:30',
            'telefono_op'  => 'nullable|string|max:30',
            'direccion'    => 'required|string|max:500',
            'direccion_op' => 'nullable|string|max:500',
        
        ]);

        $cliente = new Cliente;

        $cliente->nombre = ucfirst(strtolower($request->input('nombre')));
        $cliente->apellido = ucfirst(strtolower($request->input('apellido')));
        $cliente->cedula = $request->input( 'cedula');
        $cliente->telefono = $request->input('telefono');
        $cliente->telefono_op = $request->input('telefono_op');
        $cliente->direccion = $request->input('direccion');
        $cliente->direccion_op = $request->input('direccion_op');        

        $cliente->save();

        $notificacion = array(
                    'mensaje' => 'Se ha registrado correctamente el cliente.', 
                    'tipo' => 'success'
                    );

        return new ClienteResource($cliente);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cliente = Cliente::findOrFail($id);

        return $cliente;
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
        $validatedData = $request->validate([
            'nombre'       => 'required|string|max:30',
            'apellido'     => 'required|string|max:30',
            'telefono'     => 'required|string|max:30',
            'telefono_op'  => 'nullable|string|max:30',
            'direccion'    => 'required|string|max:500',
            'direccion_op' => 'nullable|string|max:500',
        
        ]);

        $cliente = Cliente::findOrFail($id);

        if ($request->input('nombre') != $cliente->nombre) {
            $cliente->nombre = $request->input('nombre');
        }

        if ($request->input('apellido') != $cliente->apellido) {
            $cliente->apellido = $request->input('apellido');
        }

        if ($request->input('telefono') != $cliente->telefono) {
            $cliente->telefono = $request->input('telefono');
        }

        if ($request->input('telefono_op') != $cliente->telefono_op) {
            $cliente->telefono_op = $request->input('telefono_op');
        }

        if ($request->input('direccion') != $cliente->direccion) {
            $cliente->direccion = $request->input('direccion');
        }

        if ($request->input('direccion_op') != $cliente->direccion_op) {
            $cliente->direccion_op = $request->input('direccion_op');
        }
        
        $cliente->update();

        $notificacion = array(
                    'mensaje' => 'Se ha actualizado correctamente el cliente.', 
                    'tipo' => 'success'
                    );

       /* return view('clientes.show', [
                     'cliente' => $cliente
                 ])->with($notificacion);
        */
        
        return Redirect()->back();
    }
}
