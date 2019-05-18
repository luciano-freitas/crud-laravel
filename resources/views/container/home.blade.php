@extends('layout.app')

@section('container')
    <br>
    <h1>Olá seja bem-vindo ao CRUD em Laravel</h1>
    <h4>Acesse um dos menus logo abaixo:</h4>
    <hr>
    <div class="row">
        <div class="col-md-6">
            <button class="btn btn-primary btn-block btn-lg" onclick="window.location = '/produto/index'">Cadastro de produtos</button>
        </div>
        <div class="col-md-6">
            <button class="btn btn-success btn-block btn-lg" onclick="window.location = '/pedido/index'">Cadastro de pedidos</button>
        </div>
    </div>

@endsection