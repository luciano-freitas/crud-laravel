<?php

namespace App\Http\Controllers;

use App\Iten;
use App\Pedido;
use Illuminate\Http\Request;
use App\Produto;

class PedidoController extends Controller
{

    /**
     * Retorna todos os pedidos cadastrados
     * @param null $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function getPedidos($id = NULL)
    {
        if (!is_null($id)) {
            $pedidos = Pedido::with(['iten.produto'])->where('id', $id)->get();
        } else {
            $pedidos = Pedido::with(['iten.produto'])->get();
        }

        return response($pedidos->toJson());
    }

    /**
     * Efetua a inserção ou atualização de um registro
     * @param Request $request
     * @param null $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function salvar(Request $request, $id = NULL)
    {
        $statusResponse = 200;
        $response = ["id" => $id];
        $pedido = new Pedido();
        $valorTotalPedido = 0;

        $vetor = [
            "jsonItens" => json_decode($request->input('cadPedidosJsonItens'), TRUE),
        ];

        try {
            //Update
            if (!is_null($id) && is_numeric($id) && $id > 0) {

                //Verifica se existe registro na tabela com o parâmetro passado
                if (!is_null(Pedido::find($id))) {
                    $pedido = Pedido::find($id);

                    $pedidos = Pedido::with(['iten.produto'])->where('id', $id)->get();

                    $arrayIdItens = [];
                    if (count($pedidos) > 0) {
                        foreach ($pedidos[0]->iten as $i) {
                            array_push($arrayIdItens, $i->id);
                        }
                    }

                    //Percorre item por item vindo da tela
                    foreach ($vetor['jsonItens']['iten'] as $i) {

                        $iten = (isset($id) && is_numeric($i['id']) && intval($i['id']) > 0) ? Iten::find($i['id']) : new Iten();

                        $iten->produto_id = intval($i['produto_id']);
                        $iten->qtd = intval($i['qtd']);
                        $iten->total = intval($i['qtd']) * floatval($i['total']);
                        $iten->save();

                        $valorTotalPedido += $iten->total;
                    }


                    $response["mensagem"] = SUCESSO_REGISTRO_ATUALIZAR;
                } else {
                    $statusResponse = 406;
                    $response["mensagem"] = ERRO_REGISTRO_LOCALIZAR;
                }

            } else { //Insert
                $response["mensagem"] = SUCESSO_REGISTRO_CADASTRAR;
            }


            $pedido->total = $valorTotalPedido;
            $pedido->save();

            $response["id"] = $pedido->id;
        } catch (\Exception $e) {
            $statusResponse = 500;
            $response = [
                "mensagem" => ERRO_HOUVE_UM_IMPREVISTO . $e->getMessage()
            ];
        }

        return response(json_encode($response), $statusResponse);
    }

    /**
     * Deleta um registro da tabela
     * @param null $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function deletar($id = NULL)
    {
        $statusResponse = 200;
        $response = ["id" => $id];

        try {

            //Verifica se existe registro na tabela com o parâmetro passado
            if (!is_null(Pedido::find($id))) {
                Pedido::find($id)->delete();
                $response["mensagem"] = SUCESSO_REGISTRO_DELETAR;
            } else {
                $statusResponse = 406;
                $response["mensagem"] = ERRO_REGISTRO_LOCALIZAR;
            }

        } catch (\Exception $e) {
            $statusResponse = 500;
            $response = [
                "mensagem" => ERRO_HOUVE_UM_IMPREVISTO . $e->getMessage()
            ];
        }

        return response(json_encode($response), $statusResponse);
    }

}
