<?php

namespace App\Http\Controllers;
use App\User;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\User\UsersResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {	
        $users = User::orderBy('id', 'desc')->paginate(10);
      	
        // return response()->json(['body' => new UsersResource($users),
        //                          'descripcion' => 'Consulta realizada satisfactoriamente',
        //                          'message' => 'OK',
        //                          'status' => 200], 200);
        return new UsersResource($users);
    }

    /**
     * Obtiene cantidad de usuarios admin.
     *
     * @return \Illuminate\Http\Response
     */
    public function stats()
    {        
        $usersAdmin = User::where('rol', 0)->count();                            
        $collection = collect($usersAdmin);
        
        return $collection->toJson();
       
    }

    /**
     * Create a new user.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(Request $request)
    {
        $vali = $request->validate([
            'usuario'  => 'required|string|max:15|unique:users',
            'password' => 'required|string|min:6',
            'rol'      => 'required|integer'        
        ]);

        $user = new User;

        $user->usuario = $request->input('usuario');
        $user->password = Hash::make($request->input('password'));
        $user->rol = $request->input('rol');      

        if ($user->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Usuario registrado satisfactoriamente'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Error creando el usuario'
            ], 500);
        }     
    }
}
