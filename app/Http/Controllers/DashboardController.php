<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transaccion;
use Auth;
use Carbon\Carbon;
use DB;

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
     * Retorna los registros de la semana.
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
     * Retorna los registros del dia.
     *
     * @return \Illuminate\Http\Response
     */
    public function lastDay()
    {
        $day = Carbon::now()->day;        
        $transOri = Transaccion::where('user_id',$this->user->id)
                            ->whereDay('fecha', $day)->get();

        $countReti = $transOri->where('tipo', 0)->count();
        $countDepo = $transOri->where('tipo', 1)->count();
        
        
        $max = $transOri->max('monto');
        $min = $transOri->min('monto');
        $avr = $transOri->avg('monto');
        $count = $transOri->count();        

        return response()->json([
                'count' => $count,
                'countDepo' => $countDepo,
                'countReti' => $countReti,
                'average' => $avr,
                'max' => $max,
                'min' => $min
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
        $transOri = Transaccion::where('user_id', $this->user->id)
                           ->whereYear('fecha', $year)->get();
        $max = $transOri->max('monto');
        $min = $transOri->min('monto');
        $avr = $transOri->avg('monto');
        $count = $transOri->count();

        $trans = Transaccion::where('user_id', $this->user->id)
                ->whereBetween('fecha', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()])
                ->get()
                ->groupBy(function($date) {
                    return Carbon::parse($date->fecha)->format('m'); // grouping by months
                });

        $transcount = [];
        $data = [];

        foreach ($trans as $key => $value) {
            $transcount[(int)$key] = count($value);
        }

        for($i = 1; $i <= 12; $i++){
            if(!empty($transcount[$i])){
                $data[$i] = $transcount[$i];    
            }else{
                $data[$i] = 0;    
            }
        }

        return response()->json([
                'count' => $count,
                'data' => $data,
                'average' => $avr,
                'max' => $max,
                'min' => $min
            ], 200);
    }
}
