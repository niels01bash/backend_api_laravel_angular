<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Hashing\BcryptHasher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuarios = User::get();

        return response()->json($usuarios, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required",
            "email" => "required|email|unique:users",
            "password" => "required"
        ]);

        try{
            DB::beginTransaction();

            $usuario = new User();
            $usuario->name = $request->name;
            $usuario->email = $request->email;
            $usuario->password = bcrypt($request->password);
            $usuario->save();

            DB::commit();

            return response()->json(["mensaje" => "Usuario registrado"], 201);

        }catch(\Exception $e){
            DB::rollBack();

            return response()->json(["error" => $e->getMessage()],502);

        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $usuario = User::findOrFail($id);
        return response()->json($usuario,200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            "name" => "required",
            "email" => "required|email|unique:users,email,$id"
        ]);

        try{
            DB::beginTransaction();

            $usuario = User::findOrFail($id);
            $usuario->name = $request->name;
            $usuario->email = $request->email;
            $usuario->password =$request->password;
            if(isset($request->password)){
                $usuario->password =$request->password;
            }
            $usuario->update();

            return response()->json(["mensaje" => "Usuario Actualizado"], 201);
        } catch(\Exception $e){
            DB::rollBack();
            return response()->json(["error" => $e->getMessage()], 502);

        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $usuario = User::find($id);
        //$usuario->delete();
        return response()->json(["mensaje" => "Usuario eliminado"],200);
    }
}
