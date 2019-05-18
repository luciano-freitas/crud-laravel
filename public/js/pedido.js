var produtos = [];
document.addEventListener("DOMContentLoaded", function () {
    getPedidos(montarListaDePedidos);
    getProdutos();

    $("#exampleModal").on('hidden.bs.modal', function () {
        $('#cadPedidoId').val(0);
        $('#cadPedidoFormulario tbody').html('');
    });
});

function salvar() {
    var id = $('#cadPedidoId').val();

    var json = [];
    $.each($('#cadPedidoFormulario').find('tbody').find('tr'), function (i, v) {
        var id = $(v).data('id');
        var produto_id = $(v).find('.cadPedidoProdutos').val();
        var qtd = parseInt($(v).find('.cadPedidoQtd').val());

        json.push({id: id, produto_id: produto_id, qtd: qtd});
    });

    $.ajax({
        url: `${BASE_URL}/pedido/salvar/${id}`,
        type: 'POST',
        dataType: 'json',
        data: {cadPedidosJsonItens: JSON.stringify({iten: json})}
    }).done(function (x) {
        toastr.success(x.mensagem);
        $('#exampleModal').modal('hide');
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
                        <button type="button" class="btn btn-warning btn-sm" onclick="getPedidos(setPedidoForm, ${v.id})">Editar</button>
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

function setPedidoForm(x) {
    $('#cadPedidoId').val(x[0].id);
    $.each(x[0].iten, function (i, v) {
        montarFormulario(v);
    });

    $('#exampleModal').modal('show');
}

function montarFormulario(x) {
    var html = `
        <tr data-id='${x.id}'>
          <td>
              <select class='form-control cadPedidoProdutos'>
              ${montarSelectProduto(x.produto_id)}
              </select>
          </td>
          <td><input type='number' class='form-control cadPedidoQtd' value='${x.qtd}'/></td>
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