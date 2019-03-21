<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transaccion;
use Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    protected $user;
 
    public function __construct()
    {
        $this->user = Auth::Guard('api')->user();
    }
    /**
     * Retorna la lista de contadores para las transacciones.
     *
     * @return \Illuminate\Http\Response
     */
    public function home()
    {
        $trans = Transaccion::where('user_id', $this->user->id)->get();
        $transCount = $trans->sum('monto');
        $depoCount = $trans->where('tipo', 1)->sum('monto');
        $retiroCount = $trans->where('tipo', 0)->sum('monto');

        return response()->json([
                'transCount' => $transCount,
                'depoCount' => $depoCount,
                'retiroCount' => $retiroCount,
            ], 200);
    }

    /**
     * Retorna los registros de la semana actual.
     *
     * @return \Illuminate\Http\Response
     */
    public function lastWeek()
    {
        $transOri = Transaccion::where('user_id', $this->user->id)
                            ->whereBetween('fecha', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                            ->orderBy('fecha', 'asc')
                            ->get();
        $max = $transOri->max('monto');
        $min = $transOri->min('monto');
        $avr = $transOri->avg('monto');
        $count = $transOri->count();
        $dates = collect();

        foreach( range( 0, 6 ) AS $i ) {
            $date = Carbon::now()->startOfWeek()->addDay( $i )->format( 'Y-m-d' );
            $dates->put( $date, 0);
        }
        
        $trans = Transaccion::selectRaw('date(fecha) as fecha, COUNT(*) as count')
                    ->where('user_id', $this->user->id)
                    ->whereBetween('fecha', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                    ->groupBy('fecha')
                    ->orderBy('fecha', 'ASC')
                    ->pluck('count', 'fecha');

        $data = $dates->merge( $trans );
        $data = $data->values();
        return response()->json([
                'count' => $count,
                'data' => $data,
                'average' => $avr,
                'max' => $max,
                'min' => $min
            ], 200);
    }
    /**
     * Retorna los registros del ultimo mes.
     *
     * @return \Illuminate\Http\Response
     */
    public function lastMonth()
    {
        $month = Carbon::now()->month;
        
        $trans = Transaccion::where('user_id', $this->user->id)
                            ->whereMonth('fecha', $month)->get();

        $avr = $trans->avg('monto');

        return response()->json([
                'trans' => $trans,
                'count' => $trans->count(),
                'average' => $avr
            ], 200);
    }
    /**
     * Retorna los registros del ultimo año.
     *
     * @return \Illuminate\Http\Response
     */
    public function lastYear()
    {
        $year = Carbon::now()->year;
        
        $trans = Transaccion::where('user_id', $this->user->id)
                            ->whereYear('fecha', $year)->get();

        return response()->json([
                'trans' => $trans
            ], 200);
    }
}
