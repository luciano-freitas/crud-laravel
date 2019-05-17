@extends('layout.app')

@section('container')
    <fieldset>
        <legend><h3 class="animated pulse">Cadastro de produtos</h3></legend>
        <form id="cadProdutoForm" method="POST">
            <input type="hidden" id="cadProdutoId" name="cadProdutoId" value="0">

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="cadProdutoNome">Nome do produto: </label>
                    <input type="text" class="form-control" id="cadProdutoNome" name="cadProdutoNome">
                </div>
                <div class="form-group col-md-6">
                    <label for="cadProdutoDescricao">Descrição: </label>
                    <input type="text" class="form-control" id="cadProdutoDescricao" name="cadProdutoDescricao">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="cadProdutoSku">SKU: </label>
                    <input type="text" class="form-control" id="cadProdutoSku" name="cadProdutoSku">
                </div>

                <div class="form-group col-md-6">
                    <label for="cadProdutoPreco">Preço: </label>
                    <input type="text" class="form-control" id="cadProdutoPreco" name="cadProdutoPreco" placeholder="Informe no formato: 0.00">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <button type="submit" class="btn btn-success btn-block">Salvar</button>
                </div>
            </div>
        </form>
    </fieldset>
    <hr>
    <fieldset>
        <legend><h3 class="animated pulse">Lista de produtos</h3></legend>
        <div class="table-responsive">
            <table class="table table-striped" id="cadProdutoLista">
                <thead>
                <tr>
                    <th scope="col">id</th>
                    <th scope="col">Sku</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Descrição</th>
                    <th scope="col">Preço</th>
                    <th scope="col">Ação</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </fieldset>

@endsection
<script>
    var BASE_URL = "{{Request::url()}}";
    document.addEventListener("DOMContentLoaded", function () {


        $('#cadProdutoForm').on('submit', function (e) {
            e.preventDefault();
            salvar($(this));
        });


        getProdutos(montarListaDeProdutos);
    });

    function salvar($form) {
        var id = $('#cadProdutoId').val();

        $.ajax({
            url: `${BASE_URL}/produto/salvar/${id}`,
            type: 'POST',
            dataType: 'json',
            data: $form.serialize()
        }).done(function (x) {
            toastr.success(x.mensagem);
            getProdutos(montarListaDeProdutos);
        })
    }


    function getProdutos($callback, id) {
        if (!id) id = '';
        $.get(`${BASE_URL}/produto/getProdutos/${id}`, function (x) {
            $callback(JSON.parse(x));
        });
    }

    function montarListaDeProdutos(x) {
        var html = '';
        $.each(x, function (i, v) {
            html += `
                 <tr>
                    <th scope="row">${v.id}</th>
                    <td>${v.sku}</td>
                    <td>${v.nome}</td>
                    <td>${v.descricao}</td>
                    <td>${v.preco}</td>
                    <td>
                        <button type="button" class="btn btn-warning btn-sm" onclick="getProdutos(setProdutoForm, ${v.id})">Editar</button>
                        <button type="button" class="btn btn-danger btn-sm" onclick="deletar(${v.id})">Excluir</button>
                    </td>
                </tr>`;
        });

        $("#cadProdutoLista").find('tbody').html(html);
    }

    function setProdutoForm(x) {
        if (Object.keys(x).length > 0) {
            $('#cadProdutoId').val(x.id);
            $('#cadProdutoNome').val(x.nome);
            $('#cadProdutoSku').val(x.sku);
            $('#cadProdutoDescricao').val(x.descricao);
            $('#cadProdutoPreco').val(x.preco);

            toastr.info(`Você está editando o código: ${x.id}`);
        }
    }

    function deletar(id) {
        $.ajax({
            url: `${BASE_URL}/produto/deletar/${id}`,
            type: 'DELETE',
            dataType: 'json'
        }).done(function (x) {
            toastr.success(x.mensagem);
            getProdutos(montarListaDeProdutos);
        })
    }
</script>