<?php
require("TabelaCampeonato.class.php");

$modulo2 = new TabelaCampeonato();
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
    <link rel="stylesheet" href="css/style.css">
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
                <div id="freeze">
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
    <script src="node_modules/js-loading-overlay/dist/js-loading-overlay.min.js"></script>
    <script>
        document.querySelector(".disclaimer").remove();
        document.getElementById("freeze").style.overflow = "scroll";
        function rodada(pos)
        {
            let elem = document.getElementById('nrodada');
            let rodada = elem.getAttribute('data-nrodada');
            let totalrodadas = elem.getAttribute('data-totalrodadas');
            
            let goto = parseInt(rodada)+(pos);

            if(goto <= 0) goto = 1;
            if(goto > totalrodadas) goto = totalrodadas;

            JsLoadingOverlay.show({containerID:'nrodada',spinnerSize: '1x',spinnerIcon:'ball-spin-clockwise'});

            fetch("ajax.php", {
                // Adding method type
                method: "POST",
                // Adding body or contents to send
                body: JSON.stringify({
                    acao:'rodada','goto':goto, 'fase':document.getElementById('nfase').getAttribute('data-nfase')
                })
            })            
            // Converting to JSON
            .then(response => response.json())
            // Displaying results to console          
            .then((data) => {
                JsLoadingOverlay.hide();
                document.getElementById("rodada").innerHTML = data.result;
            });
        }

        function fase(pos)
        {
            let elem = document.getElementById('nfase');
            let fase = elem.getAttribute('data-nfase');
            let totalfases = elem.getAttribute('data-totalfases');
            
            let goto = parseInt(fase)+(pos);

            if(goto <= 0) goto = 1;
            if(goto > totalfases) goto = totalfases;

            JsLoadingOverlay.show({containerID:'nrodada',spinnerSize: '1x',spinnerIcon:'ball-spin-clockwise'});

            fetch("ajax.php", {
                // Adding method type
                method: "POST",
                // Adding body or contents to send
                body: JSON.stringify({
                    acao:'fase','goto':goto
                })
            })            
            // Converting to JSON
            .then(response => response.json())
            // Displaying results to console          
            .then((data) => {
                JsLoadingOverlay.hide();
                elem.getAttribute('data-nfase',goto);
                JsLoadingOverlay.hide();
                document.getElementById("rodada").innerHTML = data.rodadaHtml;
                document.getElementById("fase").innerHTML = data.faseHtml;
                document.getElementById("classificacao").innerHTML = data.classificacaoHtml;
                document.getElementById("legenda-fase").innerHTML = data.legendaHtml;
                fase = elem.setAttribute("data-nfase",data.fase);
            });
        }
    </script>
  </body>
</html>