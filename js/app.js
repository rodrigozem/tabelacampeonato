document.getElementById("freeze").style.overflow = "auto";

function showRegulamento()
{
    fetch("ajax.php", {
        // Adding method type
        method: "POST",
        // Adding body or contents to send
        body: JSON.stringify({ acao:'regulamento' })
    })            
    .then(response => response.json())
    .then((data) => {
        Swal.fire({
            html: '<p class="pt-4"> '+data.regulamento+'</p>',
            showCloseButton: true,
            showCancelButton: false,
            showConfirmButton: false
          })
          
    });
}

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

    JsLoadingOverlay.show({containerID:'nfase',spinnerSize: '1x',spinnerIcon:'ball-spin-clockwise'});

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