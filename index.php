<?php
require("TabelaCampeonato.class.php");

$modulo2 = new TabelaCampeonato();
//$modulo2->setFaseAtual(2);
$fase = $modulo2->getFaseAtual();
$rodada = $modulo2->showJogosRodada();
$classificacao = $modulo2->showClassificacao();
$legenda = $modulo2->showLegendaFase();
$fase = $modulo2->getFaseDescricao($fase);
$modulo2->getClubesFase();

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=mb_strtolower($modulo2->getNomeCampeonato());?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <style>
        .material-symbols-outlined {            
            font-variation-settings:
            'FILL' 1,
            'wght' 400,
            'GRAD' 0,
            'opsz' 48
        }

        .icon-xs { font-size:9px; margin-right:7px;}
        .text-end { color: #ad9f9f; cursor:default;}

        table { font-size: 11px; }
        table th { font-weight:normal; }
        table thead { height: 46px;}
        table thead tr th:nth-child(even){ --bs-table-accent-bg: transparent!important;}
        table tbody {font-size:13px;}
        table tbody tr td:not(:first-child) { text-align:center;}
        table thead tr th:not(:first-child) { text-align:center;}
        table th:first-child { background-color: #FFF; position:sticky;left:0px;z-index:9999;}
        table td:nth-child(2) {background-color: #FFF;position:sticky;left:0px;z-index:9999;}

        .cla { font-size: 20px; width:20px; display:inline-block;}
        .cla-gcla { color: blue; display:inline-block; font-size:20px; }
        .cla-zcla { color: red; }
        .cla-time { font-size: 18px;padding-left:20px; }
        .cla-ultimos-jogos { border-radius: 50%;display: inline-block;height: 7px;margin-left: 2px;overflow: hidden;width: 7px; }
        .cla-ultimos-jogos--v { background-color: #51a81e; }
        .cla-ultimos-jogos--d { background-color: #f00; }
        .cla-ultimos-jogos--e { background-color: #ccc; }        
        .cla-ultimos-jogos--neutra { background-color: #ccc; }
        .cla-title { width:40% }
        .cla-title::after { width:10%;content:'CLASSIFICAÇÃO'}
        .cla-box {
            height:7px;
            width:7px;
            margin-right:5px;
        }
        
        .jogos-info { font-size: 11px; }
        .jogos-time img { height:30px;width:30px; }
        .jogos-versus span { font-size:14px; }
        .legenda { font-size: 11px; }
        @media (max-width: 768px) {
            .cla { font-size: 20px; width:50px; display:inline-block;}
            .cla-ultimos-jogos { margin-left:1px;height:5px;width:5px;}
            .cla-time { font-size: 17px;padding-left:5px; }
            .cla-title { width:80% }
            .cla-title::after { width:60%;content:'CLASSIFIC.'}
            table tbody tr td:not(:first-child) { text-align:left; }
            table thead tr th:not(:first-child) { text-align:left; }
            .legenda { font-size: 11px; margin-top: 7px;margin-bottom: 15px;}
        }
    </style>
  </head>
  <body>
    <div class="container-xl mt-4 mb-4">
        <div class="row">
            <div class="col">
                <div class="bg-success h2 display-6 text-white pt-2 pb-2 text-center"><?=$modulo2->getNomeCampeonato();?></div>
            </div>
        </div>
        <div class="row">
            <div class="col-12" id="fase">
                <?=$modulo2->showFases();?>
            </div>
            </div>
            <div class="row">
            <div class="col-lg-8 col-12">
                <div class="freeze">
                    <table class="table border-top">
                        <thead class="text-muted">
                            <tr>
                                <th class="align-middle cla-title"></th>
                                <th class="align-middle">P</th>
                                <th class="align-middle">J</th>
                                <th class="align-middle">V</th>
                                <th class="align-middle">E</th>
                                <th class="align-middle">D</th>
                                <th class="align-middle">GP</th>
                                <th class="align-middle">GC</th>
                                <th class="align-middle">SG</th>
                                <th class="align-middle">%</th>
                                <th class="align-middle">ULT.JOGOS</th>
                            </tr>
                        </thead>
                        <tbody id="classificacao">
                            <?=$classificacao;?>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center flex-wrap legenda">
                    <div><span class="ms-2"><strong>P</strong> Pontos</span></div>
                    <div><span class="ms-2"><strong>J</strong> Jogos</span></div>
                    <div><span class="ms-2"><strong>V</strong> Vitórias</span></div>
                    <div><span class="ms-2"><strong>E</strong> Empates</span></div>
                    <div><span class="ms-2"><strong>D</strong> Derrotas</span></div>
                    <div><span class="ms-2"><strong>GP</strong> Gols Pró</span></div>
                    <div><span class="ms-2"><strong>GC</strong> Gols Contra</span></div>
                    <div><span class="ms-2"><strong>SG</strong> Saldo de Gols</span></div>
                    <div><span class="ms-2"><strong>%</strong> Aproveitamento</span></div>
                </div>
                <div class="d-flex justify-content-center flex-wrap mt-2 align-items-center legenda" id="legenda-fase">
                    <?=$legenda;?>
                </div>
            </div>
            <div class="col-lg-4 col-12" id="rodada">
                <?=$rodada;?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="node_modules/js-loading-overlay/dist/js-loading-overlay.min.js"></script>
    <script>
        
        $(function(){
            $(".freeze").css('overflowX','auto');
            $(".disclaimer").remove();
        })

        function rodada(pos)
        {
            let elem = document.getElementById('nrodada');
            let rodada = elem.getAttribute('data-nrodada');
            let totalrodadas = elem.getAttribute('data-totalrodadas');
            
            let goto = parseInt(rodada)+(pos);

            if(goto <= 0) goto = 1;
            if(goto > totalrodadas) goto = totalrodadas;

            $.ajax({
                url : "ajax.php",
                type: "POST",
                data: { acao:'rodada','goto':goto, 'fase':document.getElementById('nfase').getAttribute('data-nfase') },
                beforeSend:function(){
                    JsLoadingOverlay.show({containerID:'nrodada',spinnerSize: '1x',spinnerIcon:'ball-spin-clockwise'});
                },
                success: function(data)
                {
                    JsLoadingOverlay.hide();
                    $("#rodada").html(data);
                }
            })
        }

        function fase(pos)
        {
            let elem = document.getElementById('nfase');
            let fase = elem.getAttribute('data-nfase');
            let totalfases = elem.getAttribute('data-totalfases');
            
            let goto = parseInt(fase)+(pos);

            if(goto <= 0) goto = 1;
            if(goto > totalfases) goto = totalfases;

            $.ajax({
                url : "ajax.php",
                type: "POST",
                data: { acao:'fase','goto':goto },
                dataType:'json',
                beforeSend:function(){
                    JsLoadingOverlay.show({containerID:'nfase',spinnerSize: '1x',spinnerIcon:'ball-spin-clockwise'});
                },
                success: function(data)
                {
                    elem.getAttribute('data-nfase',goto);
                    JsLoadingOverlay.hide();
                    $("#rodada").html(data.rodadaHtml);
                    $("#fase").html(data.faseHtml);
                    $("#classificacao").html(data.classificacaoHtml);
                    $("#legenda-fase").html(data.legendaHtml);
                    fase = elem.setAttribute("data-nfase",data.fase);
                }
            })
        }
    </script>
  </body>
</html>