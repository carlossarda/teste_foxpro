<?php


namespace App;

use XBase\Table;

class AcessaArquivo
{
    public function salvaRegistrosDb($dbfname) {
        $baseDir = "database/data/FoxPro/";
        $dbf        = new Table($baseDir.$dbfname);
        $nomeTabela = $this->nomeTabela($dbfname);
        $classe     = ucfirst($nomeTabela);
        $colunas    = $dbf->getColumns();
        var_dump($dbfname);

        while ($record = $dbf->nextRecord()) {
            $registro  = $this->retornaObjeto($classe);
            foreach ($colunas as $key => $coluna) {
                $nome                = $coluna->name;
                if (in_array($coluna->type,["D","T"])) {
                    $registro->$nome = $this->criaData($record->$nome,$coluna->type);
                } else if(gettype($record->$nome) == "string"){
                    $registro->$nome = utf8_encode($record->$nome);
                } else {
                    $registro->$nome = $record->$nome;
                }
            }
            $registro->save();
//            $registros[] = json_encode($registro);
        }
//        file_put_contents("base_{$dbfname}.json",implode("\n", $registros) ,FILE_TEXT);
    }

    public function listaArquivos() {
        $baseDir = "database/data/FoxPro/";
        $arquivos = array_diff(scandir($baseDir), array('..', '.'));

        return $arquivos;
    }

    public function nomeTabela($tabela) {
        preg_match("/(\w*).dbf/i",$tabela,$nome);
        $semDbf = empty($nome[1]) ? "" : strtolower($nome[1]);

        return $semDbf;
    }

    public function colunasTabela(Table $tabela){
        $colunas = $tabela->getColumns();
        return $colunas;
    }

    public function testeMigration() {
        $tabelasSchema  = $this->listaArquivos();
        foreach ($tabelasSchema as $keyTabela => $tabela) {
            if ($tabela != "cliente.dbf") {
                continue;
            }
            $baseDir = "database/data/FoxPro/";
            $arquivoDb = new Table($baseDir.$tabela);
            $colunas = $this->colunasTabela($arquivoDb);
            $count = 0;

            foreach ($colunas as $keyColuna => $coluna) {
                $tipo = $this->tipoColuna($coluna->type);
                var_dump($this->$tipo($coluna->name));
                $count++;
                if ($count>6) {
                    exit;
                }
            }
        }
    }

    public function criaTabela($table,$coluna){
        /*
        -Possible types:
//        C = Character, Memo, Varchar, Varchar (Binary)
//        D = Date
//        G = General
//        L = Logical
//        N = Numeric, Float, Double, Integer
        O = Object
//        Q = Blob, Varbinary
//        T = Datetime
//        U = Undefined/unknown
//        X = Null
//        Y = Currency
         */
        $tipo       = $coluna->type;
        $string     = ["C","G","U","X","M"];
        $float      = ["N","Y"];
        $datetime   = ["T"];
        $date       = ["D"];
        $integer    = ["I"];
        $binary     = ["Q"];
        $boolean    = ["L"];

        if (in_array($tipo,$string)) {
            return $table->string($coluna->name)->nullable();
        }
        if (in_array($tipo,$float)) {
            return $table->float($coluna->name,12,4)->nullable();
        }
        if (in_array($tipo,$datetime)) {
            return $table->datetime($coluna->name)->nullable();
        }
        if (in_array($tipo,$date)) {
            return $table->date($coluna->name)->nullable();
        }
        if (in_array($tipo,$integer)) {
            return $table->integer($coluna->name)->nullable();
        }
        if (in_array($tipo,$binary)) {
            return $table->binary($coluna->name)->nullable();
        }
        if (in_array($tipo,$boolean)) {
            return $table->boolean($coluna->name)->nullable();
        }
    }

    public function migraArquivosBanco() {
        $arquivosDb = $this->listaArquivos();

        foreach ($arquivosDb as $key => $tabela) {
            $this->salvaRegistrosDb($tabela);
        }

    }

    private function retornaObjeto($classe){
        if ($classe == "Cep") return new Cep();
        if ($classe == "Cliente") return new Cliente();
        if ($classe == "Controle") return new Controle();
        if ($classe == "Diversos") return new Diversos();
        if ($classe == "Docreceb") return new Docreceb();
        if ($classe == "Especial") return new Especial();
        if ($classe == "Feriado") return new Feriado();
        if ($classe == "Formul") return new Formul();
        if ($classe == "Grpdesc") return new Grpdesc();
        if ($classe == "Grpsex") return new Grpsex();
        if ($classe == "Horario") return new Horario();
        if ($classe == "Imagem") return new Imagem();
        if ($classe == "Indice") return new Indice();
        if ($classe == "Itemdocr") return new Itemdocr();
        if ($classe == "Itemsaex") return new Itemsaex();
        if ($classe == "Itemsex") return new Itemsex();
        if ($classe == "Laudo") return new Laudo();
        if ($classe == "Listagem") return new Listagem();
        if ($classe == "Local") return new Local();
        if ($classe == "Menu") return new Menu();
        if ($classe == "Modlaudo") return new ModLaudo();
        if ($classe == "Municipio") return new Municipio();
        if ($classe == "Parcelas") return new Parcelas();
        if ($classe == "Patolog") return new Patolog();
        if ($classe == "Preco") return new Preco();
        if ($classe == "Profiss") return new Profiss();
        if ($classe == "Programa") return new Programa();
        if ($classe == "Servico") return new Servico();
    }

    private function criaData($dataBanco, $tipo) {
//        D = Date
//        T = Datetime
//        entrada = "Sat, 01 Jan 1994 00:00:00 +0000"
//                  "Tue, 22 Jun 2004 17:51:45 +0000"
        if (empty($dataBanco)) {
            return null;
        }

        if ($tipo == "D") {
            $formatoEntrada = "D, d M Y G:i:s O";
            $formatoSaida  = "Y-m-d";
        } else {
            $formatoEntrada = "D, d M Y G:i:s O";
            $formatoSaida  = "Y-m-d H:i:s";
        }
        $dateTime   = \DateTime::createFromFormat($formatoEntrada,$dataBanco);
        $data       = $dateTime->format($formatoSaida);

        return $data;
    }
}