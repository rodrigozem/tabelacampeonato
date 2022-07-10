<?php
class TabelaCampeonato
{
    var $clubes;
    var $campeonato;   
    var $rodadas;
    var $fase;
    
    public function __construct()
    {
        $campeonatoJson = file_get_contents('json/campeonato.json');
        $this->campeonato = json_decode($campeonatoJson, true);

        $clubeJson = file_get_contents('json/clubes.json');
        $this->clubes = json_decode($clubeJson, true);

        /* Busca no arquivo JSON a fase atual */
        $this->fase = array_search($this->campeonato['fase_atual'], array_column($this->campeonato['fase'], 'id'));
        
        $this->rodadas = $this->campeonato['fase'][$this->fase]['rodadas'];
    }

    public function getTotalFases()
    {
        return count( $this->campeonato['fase'] );
    }

    public function setFaseAtual($fase)
    {
        $this->fase = array_search($fase, array_column($this->campeonato['fase'], 'id'));
        $this->rodadas = $this->campeonato['fase'][$this->fase]['rodadas'];        
    }

    public function showJogosRodada($nRodada=NULL)
    {
        $rodadas = $this->campeonato['fase'][$this->fase]['rodadas'];

        /* Mostra rodada atual caso não informado o parâmetro */
        if($nRodada == NULL)
            $nRodada = $this->getRodadaAtual();
            
        if(isset($this->rodadas[$nRodada]))
        {
            $code  = "";
            $code .= '<nav class="border-bottom border-top">
                        <ul class="nav d-flex justify-content-between align-items-center">
                            <li class="nav-item">
                                <a id="btn-rodada-prev" class="nav-link pe-5" '.($nRodada > 1 ? ' onclick="rodada(-1)" href="javascript:void(0)"' : '').'>
                                    <i style="font-size:1.5rem;" class="bi-chevron-left '.($nRodada > 1 ? 'text-success' : 'text-end').'"></i>
                                </a>
                            </li>
                            <li class="fw-bold" id="nrodada" data-totalrodadas="'.count($this->rodadas).'" data-nrodada="'.$nRodada.'">'.$nRodada.'ª RODADA</li>
                            <li class="nav-item">
                                <a id="btn-rodada-next" class="nav-link ps-5" '.(count($this->rodadas) != $nRodada ? 'onclick="rodada(1)" href="javascript:void(0)"' : '').'>
                                    <i style="font-size:1.5rem;" class="bi-chevron-right '.(count($this->rodadas) != $nRodada ? 'text-success' : 'text-end').'"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                    <ul class="list-group list-group-flush d-flex align-items-center">';

            foreach ($this->campeonato['fase'][$this->fase]['rodadas'][$nRodada] as $kRodadaj => $confronto)
            {

                $nome_mandante = $this->clubes[array_search($confronto["mandante"], array_column($this->clubes, 'id'))]["nome"];
                $nome_time_mandante = $this->clubes[array_search($confronto["mandante"], array_column($this->clubes, 'id'))]["abr"];
                $img_mandante = $this->clubes[array_search($confronto["mandante"], array_column($this->clubes, 'id'))]["img"];
    
                $nome_visitante = $this->clubes[array_search($confronto["visitante"], array_column($this->clubes, 'id'))]["nome"];
                $nome_time_visitante = $this->clubes[array_search($confronto["visitante"], array_column($this->clubes, 'id'))]["abr"];
                $img_visitante = $this->clubes[array_search($confronto["visitante"], array_column($this->clubes, 'id'))]["img"];

                $local = $confronto["local"];
                $data  = $confronto["data"];
                $hora  = $confronto["hora"];
                
                setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
                date_default_timezone_set('America/Sao_Paulo');
                $dia_semana = strftime('%a', strtotime(implode("-",array_reverse(explode("/",$data)))));
                

                $code .=   '<li class="list-group-item text-center w-100 border-0 mt-2">
                                <div class="jogos-info"><span class="fw-semibold">'.mb_strtoupper(utf8_encode($dia_semana)).' '.$data.'</span> '.$local.' <span class="fw-semibold">'.$hora.'</span></div>
                            </li>
                            <li class="list-group-item w-100 p-0">
                                <div class="d-flex justify-content-center align-items-center">
                                    <div translate="no" class="p-2 jogos-time fs-5 fw-lighter" title="'.$nome_mandante.'">'.$nome_time_mandante.' <img class="me-2" src="images/'.$img_mandante.'" alt="'.$nome_mandante.'"> </div>
                                    <div class="p-2 jogos-versus">
                                        <span class="ms-3 me-2 d-inline-block fs-4 fw-semibold">'.( ( isset($confronto["resultado"][0]) != NULL || isset($confronto["resultado"][1]) != NULL ) ? explode("x",$confronto["resultado"])[0] : '').'</span>
                                        <i class="bi bi-x-lg"></i>
                                        <span class="me-3 ms-3 d-inline-block fs-4 fw-semibold">'.( ( isset($confronto["resultado"][0]) != NULL || isset($confronto["resultado"][1]) != NULL ) ? explode("x",$confronto["resultado"])[1] : '').'</span>
                                    </div>
                                    <div translate="no" class="p-2 jogos-time fs-5 fw-lighter" title="'.$nome_visitante.'"><img class="me-2" src="images/'.$img_visitante.'" alt="'.$nome_visitante.'">'.$nome_time_visitante.'</div>
                                </div>
                            </li>';
            }
            $code .= '</ul>';
            
            return $code;
        }
    }

    public function showClassificacao()
    {
        $classificacao_rodada = array();
        $ultimos_jogos = array();

        foreach ($this->clubes as $k => $clube) {
            $this->clubes[$k]["criterios"]["p"]  = 0;
            $this->clubes[$k]["criterios"]["v"]  = 0;
            $this->clubes[$k]["criterios"]["e"]  = 0;
            $this->clubes[$k]["criterios"]["d"]  = 0;
            $this->clubes[$k]["criterios"]["sg"] = 0;
            $this->clubes[$k]["criterios"]["gp"] = 0;
            $this->clubes[$k]["criterios"]["gc"] = 0;
        }

        foreach ($this->rodadas as $kRodada => $rodada)
        {
            foreach ($this->rodadas[$kRodada] as $kRodadaj => $confronto)
            {
        
                if( isset($confronto["resultado"][0]) != NULL || isset($confronto["resultado"][1]) != NULL )
                {
                    $gols_time1 = explode("x",$confronto["resultado"])[0];
                    $gols_time2 = explode("x",$confronto["resultado"])[1];
                    
                    /* Pontos */
                    $pts_mandante = null; $pts_visitante = null;
                    $v_mandante   = null; $v_visitante   = null;
                    $e_mandante   = null; $e_visitante   = null;
                    $d_mandante   = null; $d_visitante   = null;
                    
                    if( $gols_time1 == $gols_time2 )
                    {
                        $pts_mandante = 1; $pts_visitante = 1;
                        $v_mandante   = 0; $v_visitante   = 0;
                        $e_mandante   = 1; $e_visitante   = 1;
                        $d_mandante   = 0; $d_visitante   = 0;
                    } else if ( $gols_time1 > $gols_time2 ) {
                        $pts_mandante = 3; $pts_visitante = 0;
                        $v_mandante   = 1; $v_visitante   = 0;
                        $e_mandante   = 0; $e_visitante   = 0;
                        $d_mandante   = 0; $d_visitante   = 1;
                    } else if( $gols_time1 < $gols_time2 ) {
                        $pts_mandante = 0; $pts_visitante = 3;
                        $v_mandante   = 0; $v_visitante   = 1;
                        $e_mandante   = 0; $e_visitante   = 0;
                        $d_mandante   = 1; $d_visitante   = 0;
                    }

                    /* Retorna chave do array de clubes com base na coluna ID do clube */
                    $id_mandante = array_search($confronto["mandante"], array_column($this->clubes, 'id'));
                    $id_visitante = array_search($confronto["visitante"], array_column($this->clubes, 'id'));

                    /* Pontos */
                    $this->clubes[$id_mandante]["criterios"]["p"] += $pts_mandante;
                    $this->clubes[$id_visitante]["criterios"]["p"] += $pts_visitante;
        
                    /* Vitórias */
                    $this->clubes[$id_mandante]["criterios"]["v"] += $v_mandante;
                    $this->clubes[$id_visitante]["criterios"]["v"] += $v_visitante;
        
                    /* Empates */
                    $this->clubes[$id_mandante]["criterios"]["e"] += $e_mandante;
                    $this->clubes[$id_visitante]["criterios"]["e"] += $e_visitante;
        
                    /* Derrotas */
                    $this->clubes[$id_mandante]["criterios"]["d"] += $d_mandante;
                    $this->clubes[$id_visitante]["criterios"]["d"] += $d_visitante;
        
                    /* Gols Pro */
                    $this->clubes[$id_mandante]["criterios"]["gp"] += $gols_time1;
                    $this->clubes[$id_visitante]["criterios"]["gp"] += $gols_time2;
        
                    /* Gols Contra */
                    $this->clubes[$id_mandante]["criterios"]["gc"] += $gols_time2;
                    $this->clubes[$id_visitante]["criterios"]["gc"] += $gols_time1;
        
                    $this->clubes[$id_mandante]["criterios"]["sg"] += ($gols_time1  - $gols_time2); 
                    $this->clubes[$id_visitante]["criterios"]["sg"] += ($gols_time2  - $gols_time1);

                    /* Últimos Jogos */
                    $this->clubes[$id_mandante]["ultimos_jogos"][] = ($pts_mandante == 3 ? 'v': ( $pts_mandante == 1 ? 'e' : 'd'));
                    $this->clubes[$id_visitante]["ultimos_jogos"][] = ($pts_visitante == 3 ? 'v': ( $pts_visitante == 1 ? 'e' : 'd'));

                }

                $kCriterios = array_column($this->clubes, 'criterios');


                /* Reclassifica os times */
                array_multisort (
                    array_column($kCriterios, 'p'),  SORT_DESC,
                    array_column($kCriterios, 'sg'), SORT_DESC,                    
                    array_column($kCriterios, 'gp'), SORT_DESC,
                    array_column($kCriterios, 'v'),  SORT_DESC,
                $this->clubes );
            }
        }
        
        /* Percentual de aproveitamento */
        foreach ($this->clubes as $clubeKey => $time)
        {
            $nJogos = $this->clubes[$clubeKey]["criterios"]["v"]+
                      $this->clubes[$clubeKey]["criterios"]["e"]+
                      $this->clubes[$clubeKey]["criterios"]["d"];
        
            $pontos_disputados = $nJogos * 3;
        
            $pontos_conquistados = ( $this->clubes[$clubeKey]["criterios"]["v"] * 3 ) + ( $this->clubes[$clubeKey]["criterios"]["e"] );
            
            if($pontos_disputados != 0)
            {
                $percentual_aproveitamento = number_format(( $pontos_conquistados / $pontos_disputados ), 3, '.', '');
                $this->clubes[$clubeKey]["criterios"]["perc"] = $percentual_aproveitamento*100;
            }
        }
        $code = "";
        $pos  = 0;

        /* Grava na variável clubesFase os Clubes referentes os confrontos das rodada da fase atual */
        $clubesFase = $this->getClubesFase();
        
        foreach ($this->clubes as $kTime => $time)
        {
            /* Verifica se o time está no array dos clubes da fase atual */
            if(in_array($time['id'],$clubesFase))
            {
                $pos += 1;
                $code .= '<tr>
                            <td class="align-middle d-none d-sm-block">
                                <div class="d-flex align-items-center">
                                    <span class="cla '.$this->class_posicao($pos,2).'">'.$pos.'</span>
                                    <span class="cla-time w-100">'.$time["nome"].' </span>
                                </div>
                            </td>
                            <td class="align-middle d-block d-sm-none">
                                <div class="d-flex align-items-center">
                                    <span class="cla '.$this->class_posicao($pos,2).'">'.$pos.'</span>
                                    <span class="cla-time w-100" title="'.$time["nome"].'">'.$time["abr"].'</span>
                                </div>
                            </td> 
                            <td class="align-middle bg-light"><strong>'.$time["criterios"]["p"].'</strong></td>
                            <td class="align-middle">'.($time["criterios"]["v"]+$time["criterios"]["d"]+$time["criterios"]["e"]).'</td>
                            <td class="align-middle bg-light" style>'.$time["criterios"]["v"].'</td>
                            <td class="align-middle">'.$time["criterios"]["e"].'</td>
                            <td class="align-middle bg-light">'.$time["criterios"]["d"].'</td>
                            <td class="align-middle">'.$time["criterios"]["gp"].'</td>
                            <td class="align-middle bg-light">'.$time["criterios"]["gc"].'</td>
                            <td class="align-middle">'.$time["criterios"]["sg"].'</td>
                            <td class="align-middle bg-light">'.$time["criterios"]["perc"].'</td>
                            <td class="align-middle">
                                '.$this->showUltimosJogos($time["ultimos_jogos"]).'
                            </td>
                        </tr>';
            }
        }
        return $code;
    }

    public function showFases()
    {
        $nFase = $this->getFaseId();
        
        $code = '<ul class="nav d-flex justify-content-between align-items-center mb-2 bg-light">
                    <li class="nav-item pt-1">
                        <a id="btn-fase-prev" class="nav-link btn-fase" '.($nFase > 1 ? ' onclick="fase(-1)" href="javascript:void(0)"' : '').'>
                            <i style="font-size:1.5rem;" class="bi-chevron-left '.($nFase > 1 ? 'text-success' : 'text-end').'"></i>
                        </a>
                    </li>
                    <li class="nav-item fw-bolder" id="nfase" data-totalfases="'.$this->getTotalFases().'" data-nfase="'.$this->getFaseId().'">'.$this->getFaseDescricao().'</li>
                    <li class="nav-item pt-1">
                        <a id="btn-fase-next" class="nav-link btn-fase" '.( $this->getTotalFases() != $nFase ? 'onclick="fase(1)" href="javascript:void(0)"' : '' ).'">
                            <i style="font-size:1.5rem;" class="bi-chevron-right '.($this->getTotalFases() != $nFase ? 'text-success' : 'text-end').'"></i>
                        </a>
                    </li>
                </ul>';
        
        return $code;
    }

    public function showLegendaFase()
    {
        $class = $this->campeonato['fase'][$this->fase]['classificacao'];

        $code = "";
        foreach ($class as $key => $cla) {
            $code .= '<div class="cla-box ms-2 '.$cla['class_legenda'].'"></div><span>'.$cla['descricao'].'</span>';            
        }
        $code .= '<div class="d-flex justify-content-center align-items-center">';
        $code .= '<div class="ms-2 me-1 cla-ultimos-jogos cla-ultimos-jogos--v"></div> Vitória';
        $code .= '<div class="ms-2 me-1 cla-ultimos-jogos cla-ultimos-jogos--d"></div> Derrota';
        $code .= '<div class="ms-2 me-1 cla-ultimos-jogos cla-ultimos-jogos--e"></div> Empate';
        $code .= '<a class="ms-5" id="btn-regulamento" href="javascript:void(0)" onclick="showRegulamento()">REGULAMENTO</a>';
        $code .= '</div>';

        return $code;
    }

    public function getClubesFase()
    {
        $clubes = [];
        foreach ($this->rodadas as $kRodada => $rodada)
        {
            foreach ($this->rodadas[$kRodada] as $kRodadaj => $confronto)
            {
                $clubes[] = $confronto['mandante'];
                $clubes[] = $confronto['visitante'];
            }
        }
        $clubes = array_unique($clubes);      

        return $clubes;
    }

    public function showUltimosJogos($arrUltimosJogos)
    {
        $code = "";
        
        $arrUltimosJogos = array_reverse($arrUltimosJogos);
        $arrUltimosJogos = array_slice($arrUltimosJogos, 0, 5);
        $arrUltimosJogos = array_reverse($arrUltimosJogos);
        
        for($i=0;$i<=4;++$i)
        {
            if(isset($arrUltimosJogos[$i]))
            {
                if($arrUltimosJogos[$i] == 'v')
                    $code .= '<span class="cla-ultimos-jogos cla-ultimos-jogos--v"></span>';
                else if($arrUltimosJogos[$i] == 'd')
                    $code .= '<span class="cla-ultimos-jogos cla-ultimos-jogos--d"></span>';
                else if($arrUltimosJogos[$i] == 'e')
                    $code .= '<span class="cla-ultimos-jogos cla-ultimos-jogos--e"></span>';
            }
        }

        return $code;
    }

    public function getRodadaAtual()
    {
        $rodadas = $this->rodadas;
        
        $r1 = [];
        foreach ($rodadas as $kRodada => $rodada) {
            $r1[$kRodada]['data'] = array_column($rodada,'data');
            $r1[$kRodada]['resultado'] = array_column($rodada,'resultado');
        }

        $rodada_completa = true;

        foreach ($r1 as $kData => $row) {
            $dt = array();
            $dt = array_map(array($this, 'reverse_date'),$row['data']);

            foreach ($row['resultado'] as $key => $r) {
                if(is_null($r))
                    $rodada_completa = false;    
            }

            $r2[$kData]['data'] = $this->getMaxDate($dt);
            $r2[$kData]['rodada_completa'] = $rodada_completa;
        }

        //var_dump($r2);

        $i = 1;
        foreach ($r2 as $k => $v) {
            $r3[$i]['data'] = strtotime($v['data']);
            $r3[$i]['rodada_completa'] = $v['rodada_completa'];
            ++$i;
        }        
        //var_dump($r3);
        $dtz = new DateTimeZone("America/Sao_Paulo");
        $dt = new DateTime("now", $dtz);

        $today = strtotime($dt->format("Y-m-d"));

        /* Verifica se todos os confrontos de todas as rodadas foram realizados */
        $confrontos_realizados = true;
        foreach($r3 as $k => $v)
            if($v['rodada_completa'] == false)
                $confrontos_realizados = false;

        if(!$confrontos_realizados)
        {
            foreach($r3 as $k => $v)
            {
                if( count($r3) > 1 )
                    if($v['rodada_completa'])
                        if($v['data'] < $today)
                            unset($r3[$k]);
            }
            $date = min(array_column($r3,'data'));
            
            $r4 = array_column($r2,'data');
            
            array_unshift($r4, "-");
            unset($r4[0]);    

            $rodada = array_search(date('Y-m-d',$date), $r4);
            
            /* Caso a rodada termine no sábado, a rodada com os jogos ficam sendo mostradas até no domingo  */
            if( $dt->format("N") >=6 && $dt->format("N") <= 7 )
                --$rodada;
        } else {
            $rodada = count($r3);
        }

        return $rodada;
    }

    public function getRegulamento() {
        return $this->campeonato['regulamento'];
    }

    /* Verifica se todos os jogos possuem resultados */
    public function isRodadaAtualCompleta()
    {
        $rr = $this->rodadas[ $this->getRodadaAtual() ];
        $rodada_incompleta = TRUE;
        foreach ($rr as $k => $r) {
            if( $r['resultado'] == null )
                $rodada_incompleta = FALSE;
        }

        return $rodada_incompleta;
    }

    public function getNomeCampeonato() {
        return $this->campeonato["descricao"];
    }

    public function getFaseAtual() {
        return $this->fase;
    }

    public function getFaseDescricao() {
        return $this->campeonato['fase'][$this->fase]['descricao'];
    }

    public function getFaseId() {
        return $this->campeonato['fase'][$this->fase]['id'];
    }

    /* Retorna o nome da classe css de acordo com a posição e fase informada nos parâmetros */
    private function class_posicao($pos,$fase_id)
    {
        $class = $this->campeonato['fase'][$this->fase]['classificacao'];
        
        foreach ($class as $key => $cla) {
            if( $pos >= $cla['posicao_inicial'] && $pos <= $cla['posicao_final'] )
                return $cla["class_color"];
        }
    }

    /* Verifica se o número é positivo, negativo ou zero */
    private function CheckNumber($x) {
        if ($x > 0)
          {$message = "negative";}
        if ($x == 0)
          {$message = "zero";}
        if ($x < 0)
          {$message = "positive";}
        
        return $message;
    }

    /* Retorna a maior data de um array de datas */
    public function getMaxDate($date_arr)
    {
        usort($date_arr, function($a, $b) {
            $dateTimestamp1 = strtotime($a);
            $dateTimestamp2 = strtotime($b);
    
            return $dateTimestamp1 < $dateTimestamp2 ? -1: 1;
        });
    
        return $date_arr[count($date_arr) - 1];
    }

    /* Busca no array de datas a data mais próxima da data informada no segundo parâmetro */
    public function find_closest($dates, $findate)
    {
        $newDates = array();

        foreach($dates as $date)
        {
            $newDates[] = strtotime($date);
        }

        sort($newDates);
        foreach ($newDates as $a)
        {
            if ($a >= strtotime($findate))
                return $a;
        }
        return end($newDates);
    }
    
    public function reverse_date($date) {
        return implode('-',array_reverse(explode('/',$date)));
    }
}