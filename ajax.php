<?php
require("TabelaCampeonato.class.php");
$modulo2 = new TabelaCampeonato();

$acao = $_REQUEST['acao'];

switch($acao){

    case 'rodada':
        $modulo2->setFaseAtual($_REQUEST['fase']);

        $rodada = $modulo2->showJogosRodada($_REQUEST['goto']);
        
        echo $rodada;
        
        break;
    
    case 'fase':
        $modulo2->setFaseAtual($_REQUEST['goto']);
        
        $fase          = $modulo2->showFases($_REQUEST['goto']);        
        $rodada        = $modulo2->showJogosRodada($modulo2->getRodadaAtual());
        $classificacao = $modulo2->showClassificacao();
        $legenda       = $modulo2->showLegendaFase();

        $arrResult = [
            'rodadaHtml'       => $rodada,
            'legendaHtml'      => $legenda,
            'faseHtml'         => $fase,
            'classificacaoHtml'=> $classificacao,
            'fase' => $modulo2->getFaseId($modulo2->getFaseAtual())
        ];
        
        echo json_encode($arrResult);

        break;
    
    }
    

//$modulo2->getClubes();


