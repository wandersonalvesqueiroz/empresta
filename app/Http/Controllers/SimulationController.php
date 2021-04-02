<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;

class SimulationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            
            if (!is_float($request->valor_emprestimo) || empty($request->valor_emprestimo)) {
                throw new Exception('Valor de empréstimo inválido!');
            }

            $path = storage_path('app\/json');

            $json = json_decode(file_get_contents($path . "\/taxas_instituicoes.json"));

            if (is_array($request->instituicoes) && !empty($request->instituicoes)) {
                $json = array_filter($json, function ($obj) use ($request) {
                    foreach ($request->instituicoes as $instituicao) {
                        if ($obj->instituicao == $instituicao) {
                            return true;
                        }
                    }
                });
            } elseif (isset($request->instituicoes) && !is_array($request->instituicoes)) {
                throw new Exception('Instituições com formato inválido!');
            }

            if (is_array($request->convenios) && !empty($request->convenios)) {
                $json = array_filter($json, function ($obj) use ($request) {
                    foreach ($request->convenios as $convenio) {
                        if ($obj->convenio == $convenio) {
                            return true;
                        }
                    }
                });
            } elseif (isset($request->convenios) && !is_array($request->convenios)) {
                throw new Exception('Convênios com formato inválido!');
            }

            if (is_integer($request->parcelas) && $request->parcelas > 0) {
                $json = array_filter($json, function ($obj) use ($request) {
                    if ($obj->parcelas == $request->parcelas) {
                        return true;
                    }
                });
            } elseif(isset($request->parcelas) && !is_integer($request->parcelas)) {
                throw new Exception('Parcelas com formato inválido!');
            }

            $result = [];
            foreach ($json as $value) {
                $instituicao = $value->instituicao;
                if (!isset($result[$instituicao])) $result[$instituicao] = [];

                $data = new class {};
                $data->taxas = $value->taxaJuros;
                $data->parcelas = $value->parcelas;
                $data->valor_parcela = $request->valor_emprestimo * $value->coeficiente;
                $data->convenio = $value->convenio;

                $result[$instituicao][] = $data;
            }

            return json_encode($result);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
