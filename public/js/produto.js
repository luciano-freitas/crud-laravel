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
        limparFormulario();
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

function limparFormulario() {
    $('form').trigger("reset");
    $('#cadProdutoId').val(0);
}