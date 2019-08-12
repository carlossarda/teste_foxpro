<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CriaTabelasVfp extends Migration
{
    private $colunas;
    private $acessaArquivo;

    public function up()
    {
        $this->acessaArquivo    = new \App\AcessaArquivo();
        $tabelas                = $this->acessaArquivo->listaArquivos();

        foreach ($tabelas as $key => $tabela) {
            $nome           = $this->acessaArquivo->nomeTabela($tabela);
            $nomeTabela     = strtolower($nome);
            $baseDir        = "database/data/FoxPro/";
            $arquivoDb      = new \XBase\Table($baseDir.$tabela);
            $this->colunas  = $this->acessaArquivo->colunasTabela($arquivoDb);

            Schema::create($nomeTabela, function (Blueprint $table) {
                $colunas = $this->colunas;
                foreach ($colunas as $key =>$coluna) {
                    $this->acessaArquivo->criaTabela($table,$coluna);
                }
                $table->datetime('created_at')->nullable();
                $table->datetime('updated_at')->nullable();
            });
        }
    }

    public function down()
    {
        //
    }
}
