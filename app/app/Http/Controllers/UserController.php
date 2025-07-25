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
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validação dos dados recebidos
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'is_admin' => 'required|boolean', // Validando o campo 'role' como booleano
        ]);

        // Criação do usuário
        $user = \App\Models\User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'is_admin' => $validatedData['is_admin'], // Atribuindo o valor do campo 'role'
        ]);

        // Retorno de resposta
        return response()->json(['message' => 'Usuário criado com sucesso!', 'user' => $user], 201);
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
