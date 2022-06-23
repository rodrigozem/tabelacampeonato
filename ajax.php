<?php
require("TabelaCampeonato.class.php");
$modulo2 = new TabelaCampeonato();

$request_body = file_get_contents('php://input');
$data = json_decode($request_body);

$acao = $data->acao;

switch($acao){

    case 'rodada':
        $modulo2->setFaseAtual($data->fase);

        $rodada = $modulo2->showJogosRodada($data->goto);
                
        echo json_encode(['result' => $rodada]);
        
        break;
    
    case 'fase':
        $modulo2->setFaseAtual($data->goto);
        
        $fase          = $modulo2->showFases($data->goto);        
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


