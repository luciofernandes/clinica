<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class   UserController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

         $users = User::paginate(10);

        // Retorno de resposta
        return view('user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
//        // Validação dos dados recebidos
//        $validatedData = $request->validate([
//            'name' => 'required|string|max:255',
//            'email' => 'required|string|email|max:255|unique:users',
//            'password' => 'required|string|min:8|confirmed',
//        ]);

        // Criação do usuário
        $user = \App\Models\User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
            'is_admin' => $request['is_admin'], // Atribuindo o valor do campo 'role'
        ]);

        // Retorno de resposta
        return redirect()->route('user.index')->with('status', 'Usuário criado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('user.index')->with('status', 'Usuário excluído logicamente.');
    }

}
