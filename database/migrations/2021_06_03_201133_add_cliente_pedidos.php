<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClientePedidos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('pedidos', function ($table) {
            $table->string('cliente_nome',45)->after('cliente');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('pedidos', function ($table) {
            $table->dropColumn('cliente_nome');
        });
    }
}
