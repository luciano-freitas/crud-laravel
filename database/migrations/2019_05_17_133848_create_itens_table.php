<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itens', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('pedido_id')->unsigned();
            $table->bigInteger('produto_id')->unsigned();
            $table->integer('qtd');
            $table->decimal('total');
            $table->timestamps();

            $table->foreign('pedido_id')->references('id')->on('pedidos')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('produto_id')->references('id')->on('produtos')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('itens', function (Blueprint $table) {
            $table->dropForeign(['pedido_id']);
            $table->dropColumn(['produto_id']);
        });

        Schema::dropIfExists('itens');
    }
}
