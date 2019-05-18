@extends('layout.app')

@section('container')
    <br>
    <div class="row">
        <div class="col-md-6">
            <a href="{{url('/')}}">Página inicial</a>
        </div>
    </div>
    <hr>
    <fieldset>
        <legend><h3 class="animated pulse">Lista de pedidos</h3></legend>
        <div class="form-row">
            <div class="form-group col-md-4">
                <button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#exampleModal">
                    Gerar um novo pedido
                </button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped" id="cadPedidoLista">
                <thead>
                <tr>
                    <th scope="col">id</th>
                    <th scope="col">Total</th>
                    <th scope="col">Ação</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </fieldset>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Gerar pedido</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <button class="btn btn-primary" onclick="montarFormulario({id:0,unitario:'',qtd:'',total:''})">
                        Adicionar item
                    </button>
                    <hr>
                    <h4>Lista de itens:</h4>
                    <div class="row">
                        <table class="table table-striped" id="cadPedidoFormulario">
                            <thead>
                            <tr>
                                <th scope="col" style="width: 250px;">Produto</th>
                                <th scope="col">Unitário</th>
                                <th scope="col">Qtd</th>
                                <th scope="col">Total</th>
                                <th scope="col">Excluir</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-success">Salvar pedido</button>
                </div>
            </div>
        </div>
    </div>

@endsection
<script>
    var BASE_URL = "{{url('/')}}";
    var produtos = [];
    document.addEventListener("DOMContentLoaded", function () {

        $('#cadPedidoForm').on('submit', function (e) {
            e.preventDefault();
            salvar($(this));
        });

        getPedidos(montarListaDePedidos);
        getProdutos();
    });

    function salvar($form) {
        var id = $('#cadPedidoId').val();

        $.ajax({
            url: `${BASE_URL}/pedido/salvar/${id}`,
            type: 'POST',
            dataType: 'json',
            data: $form.serialize()
        }).done(function (x) {
            toastr.success(x.mensagem);
            limparFormulario();
            getPedidos(montarListaDePedidos);
        })
    }

    function getPedidos($callback, id) {
        if (!id) id = '';
        $.get(`${BASE_URL}/pedido/getPedidos/${id}`, function (x) {
            $callback(JSON.parse(x));
        });
    }

    function montarListaDePedidos(x) {
        var html = '';
        $.each(x, function (i, v) {
            html += `
                 <tr>
                    <th scope="row">${v.id}</th>
                    <td>${v.total}</td>
                    <td>
                        <button type="button" class="btn btn-warning btn-sm" onclick="getPedidos(setProdutoForm, ${v.id})">Editar</button>
                        <button type="button" class="btn btn-danger btn-sm" onclick="deletar(${v.id})">Excluir</button>
                    </td>
                </tr>`;
        });

        $("#cadPedidoLista").find('tbody').html(html);
    }

    function deletar(id) {
        $.ajax({
            url: `${BASE_URL}/pedido/deletar/${id}`,
            type: 'DELETE',
            dataType: 'json'
        }).done(function (x) {
            toastr.success(x.mensagem);
            getPedidos(montarListaDePedidos);
        })
    }

    function limparFormulario() {
        $('form').trigger("reset");
        $('#cadPedidoId').val(0);
    }


    function montarFormulario(x) {
        var html = `
        <tr>
          <td>
              <select class='form-control cadPedidoProdutos'>
              ${montarSelectProduto(x.id)}
              </select>
          </td>
          <td><input type='text' class='form-control cadPedidoUnitario' value='${x.unitario}'/></td>
          <td><input type='number' class='form-control cadPedidoQtd' value='${x.qtd}'/></td>
          <td><input type='text' class='form-control cadPedidoTotal' value='${x.total}'/></td>
          <td>
              <button class='btn btn-block btn-danger' onclick="$(this).closest('tr').remove()">Excluir</button>
         </td>
        </tr>
        `;

        $('#cadPedidoFormulario').find('tbody').append(html);
    }

    function getProdutos() {
        $.get(`${BASE_URL}/produto/getProdutos`, function (x) {
            produtos = JSON.parse(x);
        });
    }

    function montarSelectProduto(id) {
        var html = "";

        $.each(produtos, function (i, v) {
            html += `<option value='${v.id}'${(id && parseInt(id) === parseInt(v.id)) ? 'selected' : ''}>${v.nome}</option>`;
        });

        return html;
    }
</script>