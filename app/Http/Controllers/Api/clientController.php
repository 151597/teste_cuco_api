<?php

namespace App\Http\Controllers\Api;

use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Clients;
use DB;

class clientController extends Controller
{

    /**
     * Lista os Usuários cadastrados.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $clients = Clients::select('id','nome', 'cpf', DB::raw("date_format(data_nascimento,'%d/%m/%Y') as data_nascimento"), 'telefone')->get();
        return response()->json($clients);
    }

    /**
     * Salva um novo Usuário.
     *
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        // return response()->json($request);
        $validator = Validator::make($request->all(), 
                      [ 
                      'nome' => 'required',
                      'cpf' => 'required',
                      'dateFormatted' => 'required' 
                     ]);  

         if ($validator->fails()) {  

               return response()->json(['error'=>$validator->errors()], 401); 

            }   

        $user = new Clients();
        $user->nome = $request->nome;
        $user->cpf = $request->cpf;
        $user->data_nascimento = $request->dateFormatted;
        $user->telefone = $request->telefone;
        $user->save();


        return response()->json([
            'success' => true,
            'data' => $user
        ], Response::HTTP_OK);
    }

    /**
     * Verifica se um cpf já existe.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($cpf)
    {
        $cpf = Clients::select('cpf')->where('cpf', $cpf)->first();
        if($cpf){
            return response()->json([
                'exists' => true,
                'data' => $cpf
            ], Response::HTTP_OK);
        }
        return response()->json($cpf);
    }

    /**
     * Atualza o usuário pelo ID.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        $update = new Clients();
        $update->where('id', $id)
            ->update([
                'nome' => $request->nome, 
                'cpf' => $request->cpf, 
                'data_nascimento'=> $request->dateFormatted, 
                'telefone' => $request->telefone
            ]);

        return response()->json([
            'success' => true,
            'data' => $update
        ], Response::HTTP_OK);
    }

    /**
     * Exlcui o usuário pelo ID.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        $delete = new Clients();
        $delete->where('id', $id)->delete();

        return response()->json([
            'success' => true,
            'data' => $delete
        ], Response::HTTP_OK);
    }
}
