<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Mail;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $users = User::orderBy('name', 'asc')->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.add');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required',
        ]);

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 8; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        
        $existingUser = User::where('email', $request->email)->withTrashed()->first();
       

        if (isset($existingUser)) {
            if ($existingUser->deleted_at != null) {
                //Se reactiva
                $existingUser->deleted_at = null;
                $existingUser->password = Hash::make($randomString);
                $existingUser->save();

                $destinatario = $existingUser->email;
                $msg = "Ha sido invitad@ a colaborar en el sistema de Breaker G. \n\n "  .
    
                    "Para ingresar al sistema ingrese a: " . env('APP_URL') . "\n" .
                    "Su usario es: " . $existingUser->email . " \n" .
                    "Contraseña temporal: " . $randomString;
    
                $data = [];
    
                try {
                    Mail::send(['email' => 'xxx'], $data, function ($message) use ($destinatario, $msg) {
                        $message->from('no-reply@breakerg.com', 'BreakerG');
                        $message->to($destinatario);
                        $message->subject('Bienvenido a Breaker G');
                        $message->setBody($msg);
                    });
                } catch (\Throwable $th) {
                }
    
                return redirect('users')->with('success', 'Usuario creado correctamente. Revise la bandeja de entrada del usuario.');


            } else {
                //Avisar que ya existe
                return redirect('users')->with('danger', 'El usuario ingresado ya existe.');
            }
        } else {
          

            $User = new User();
            $User->name = $request->name;
            $User->email = $request->email;
            $User->password = Hash::make($randomString);

            if (isset($request->admin))
                $User->admin = true;
            else
                $User->admin = false;

            $User->save();

            $destinatario = $User->email;
            $msg = "Ha sido invitad@ a colaborar en el sistema de Breaker G. \n\n "  .

                "Para ingresar al sistema ingrese a: " . env('APP_URL') . "\n" .
                "Su usario es: " . $User->email . " \n" .
                "Contraseña temporal: " . $randomString;

            $data = [];

            try {
                Mail::send(['email' => 'xxx'], $data, function ($message) use ($destinatario, $msg) {
                    $message->from('no-reply@breakerg.com', 'BreakerG');
                    $message->to($destinatario);
                    $message->subject('Bienvenido a Breaker G');
                    $message->setBody($msg);
                });
            } catch (\Throwable $th) {
            }

            return redirect('users')->with('success', 'Usuario creado correctamente. Revise la bandeja de entrada del usuario.');
        }
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
        ]);

        $User = User::find($id);
        $User->name = $request->name;
        $User->email = $request->email;

        if (isset($request->admin))
            $User->admin = true;
        else
            $User->admin = false;

        $User->save();

        return redirect('users')->with('success', 'Usuario editado correctamente.');
    }

    public function destroy($id)
    {
        User::destroy($id);
        return redirect('users')->with('success', 'Usuario eliminado correctamente.');
    }


    public function changePassword()
    {
        $user = User::findOrFail(Auth::user()->id);
        return view('users.changePassword', compact('user'));
    }

    public function updatePassword(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect('users/changePassword')->with('success', 'Contraseña cambiada correctamente.');
    }
}
