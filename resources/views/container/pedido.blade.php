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
                    <button class="btn btn-primary"
                            onclick="montarFormulario({id:0,produto_id:0,unitario:'',qtd:'',total:''})">
                        Adicionar item
                    </button>
                    <hr>
                    <h4>Lista de itens:</h4>
                    <div class="row">
                        <input type="hidden" id="cadPedidoId" name="cadPedidoId" value="0"/>
                        <table class="table table-striped" id="cadPedidoFormulario">
                            <thead>
                            <tr>
                                <th scope="col" style="width: 250px;">Produto</th>
                                <th scope="col">Qtd</th>
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
                    <button type="button" class="btn btn-success" onclick="salvar()">Salvar pedido</button>
                </div>
            </div>
        </div>
    </div>

@endsection
<script src="{{asset('js/pedido.js')}}" type="text/javascript"></script>