<?php

namespace App\Console\Commands;

use App\AcessaArquivo;
use Illuminate\Console\Command;

class AcessaBanco extends Command
{
    protected $signature = 'vfp:migrar';

    protected $description = 'Teste ao banco vfp';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $acessaArquivo  = new AcessaArquivo();
        $acessaArquivo->migraArquivosBanco();
    }
}
