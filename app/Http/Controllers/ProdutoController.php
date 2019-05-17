<?php

namespace App\Http\Controllers;

use App\Pedido;
use Illuminate\Http\Request;
use App\Produto;

class ProdutoController extends Controller
{

    /**
     * Retorna todos os produtos cadastrados
     * @param null $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function getProdutos($id = NULL)
    {
        if (!is_null($id)) {
            $produtos = Produto::find($id);
        } else {
            $produtos = Produto::all();
        }

        return response($produtos->toJson());
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
        $produto = new Produto();

        $vetor = [
            "nome" => $request->input('cadProdutoNome'),
            "sku" => $request->input('cadProdutoSku'),
            "descricao" => $request->input('cadProdutoDescricao'),
            "preco" => floatval($request->input('cadProdutoPreco'))
        ];

        try {
            //Update
            if (!is_null($id) && is_numeric($id) && $id > 0) {

                //Verifica se existe registro na tabela com o parâmetro passado
                if (Produto::find($id)->count() > 0) {
                    $produto = Produto::find($id);

                    $response["mensagem"] = SUCESSO_REGISTRO_ATUALIZAR;
                } else {
                    $statusResponse = 406;
                    $response["mensagem"] = ERRO_REGISTRO_LOCALIZAR;
                }

            } else { //Insert
                $response["mensagem"] = SUCESSO_REGISTRO_CADASTRAR;
            }


            $produto->nome = $vetor["nome"];
            $produto->sku = $vetor["sku"];
            $produto->descricao = $vetor["descricao"];
            $produto->preco = $vetor["preco"];
            $produto->save();

            $response["id"] = $produto->id;
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
            if (!is_null(Produto::find($id))) {
                Produto::find($id)->delete();
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
