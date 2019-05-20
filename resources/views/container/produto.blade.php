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
                    <input type="text" class="form-control" id="cadProdutoPreco" name="cadProdutoPreco"
                           placeholder="Informe no formato: 0.00">
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
                    <th scope="col">Data de cadastro</th>
                    <th scope="col">Data de atualização</th>
                    <th scope="col">Ação</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </fieldset>

@endsection
<script src="{{asset('js/produto.js')}}" type="text/javascript"></script>