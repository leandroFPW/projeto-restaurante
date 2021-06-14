<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfigsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('configs')->insert([
            'descricao' => 'Nomes das Mesas separados por vírgula',
            'chave' => 'lista_mesas',
            'valor' => '1,2,3,4,5,6,7,8',
        ]);
        DB::table('configs')->insert([
            'descricao' => 'Limite de Comandas na Cozinha',
            'chave' => 'limite_comandas',
            'valor' => '4',
        ]);
        DB::table('configs')->insert([
            'descricao' => 'Tempo de Atualização Tela da Cozinha (segundos)',
            'chave' => 'tempoat_cozinha',
            'valor' => '5',
        ]);
        DB::table('configs')->insert([
            'descricao' => 'Tamanho da fonte Comandas da Cozinha',
            'chave' => 'tamanho_fonte_cozinha',
            'valor' => '18px',
        ]);
    }
}
