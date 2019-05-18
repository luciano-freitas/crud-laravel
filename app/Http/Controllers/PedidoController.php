<?php

namespace App\Http\Controllers;

use App\Iten;
use App\Pedido;
use Illuminate\Http\Request;
use App\Produto;

class PedidoController extends Controller
{

    /**
     * Exibir a view de pedido
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('container/pedido');
    }

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

                    //Cria um array de ids de todos os itens cadastrados
                    if (count($pedidos) > 0) {
                        foreach ($pedidos[0]->iten as $i) {
                            array_push($arrayIdItens, $i->id);
                        }
                    }

                    //Percorre item por item vindo da tela
                    foreach ($vetor['jsonItens']['iten'] as $i) {

                        //Update de itens
                        if (isset($id) && is_numeric($i['id']) && intval($i['id']) > 0) {

                            $keyPosicao = array_search(intval($i['id']), $arrayIdItens);//Retorna a posição no array de ids cadastrados caso encontre
                            if (is_numeric($keyPosicao)) unset($arrayIdItens[$keyPosicao]);//Remove do array os ids já cadastrados

                            $iten = Iten::find($i['id']);
                        } else {//Insert de itens
                            $iten = new Iten();
                        }

                        //Retornando o objeto do produto
                        $produto = Produto::find($i['produto_id']);

                        //Salvando item por item
                        $iten->pedido_id = intval($pedido->id);
                        $iten->produto_id = intval($produto->id);
                        $iten->qtd = intval($i['qtd']);
                        $iten->total = intval($i['qtd']) * floatval($produto->preco);
                        $iten->save();

                        $valorTotalPedido += $iten->total;
                    }

                    //Deleta os itens que foram removidos da lista
                    if (count($arrayIdItens) > 0) {
                        foreach ($arrayIdItens as $i) {
                            Iten::find($i)->delete();
                        }
                    }

                    $response["mensagem"] = SUCESSO_REGISTRO_ATUALIZAR;
                } else {
                    $statusResponse = 406;
                    $response["mensagem"] = ERRO_REGISTRO_LOCALIZAR;
                }

            } else { //Insert

                $pedido->total = 0;
                $pedido->save();

                //Percorre item por item vindo da tela
                foreach ($vetor['jsonItens']['iten'] as $i) {
                    $produto = Produto::find($i['produto_id']);
                    $iten = new Iten();

                    $iten->pedido_id = intval($pedido->id);
                    $iten->produto_id = intval($produto->id);
                    $iten->qtd = intval($i['qtd']);
                    $iten->total = intval($i['qtd']) * floatval($produto->preco);
                    $iten->save();

                    $valorTotalPedido += $iten->total;
                }

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
