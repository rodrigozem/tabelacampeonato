<?php
require("class/TabelaCampeonato.class.php");
$modulo2 = new TabelaCampeonato();
?>
<!doctype html>
<html lang="pt-BR">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>tabela | <?=mb_strtolower($modulo2->getNomeCampeonato());?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">
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
                                <th class="align-middle cla-title" style="width:300px;"></th>
                                <th class="align-middle thw">P</th>
                                <th class="align-middle thw">J</th>
                                <th class="align-middle thw">V</th>
                                <th class="align-middle thw">E</th>
                                <th class="align-middle thw">D</th>
                                <th class="align-middle thw">GP</th>
                                <th class="align-middle thw">GC</th>
                                <th class="align-middle thw">SG</th>
                                <th class="align-middle thw">%</th>
                                <th class="align-middle">ULT.JOGOS</th>
                            </tr>
                        </thead>
                        <tbody id="classificacao">
                            <?=$modulo2->showClassificacao();?>
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
                    <?=$modulo2->showLegendaFase();?>
                </div>
            </div>
            <div class="col-lg-4 col-12" id="rodada">
                <?=$modulo2->showJogosRodada();?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.18/dist/sweetalert2.all.min.js"></script>
    <script src="node_modules/js-loading-overlay/dist/js-loading-overlay.min.js"></script>
    <script src="js/app.js"></script>
  </body>
</html>