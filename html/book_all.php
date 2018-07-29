<!DOCTYPE HTML>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Prova gráfica - Completa</title>
  <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/pure-min.css">
  <style type="text/css">
    ul {
      padding:0;
    }
    li {
      list-style: none;
    }
    .content {
      width: 1024px;
      margin: 0 auto;
    }
.info {
color: #a79137;
}
h1,h2,h3 {
color: #bb3238;
}
strong {
color: #bb3238;
}
p.rationale {
  padding-right: 10px;
}
p, .refs li {
text-align:justify;
}
.spp {
padding-bottom: 15px;
border-bottom: 1px #a79137 solid;
}
.refs li {
  margin-bottom: 8px;
}
@media print {
  .print {display: none;}
  .content {
    display: block;
    position: relative;
  }
  .spp {
    page-break-inside: avoid;
    display: block;
    position: relative;
  }
}
  </style>
<script type="text/javascript">
window.onload=function(){
  var imgs = document.querySelectorAll('img');
  for(var i=0;i<imgs.length;i++){
    if(imgs[i].naturalWidth==0) {
       imgs[i].remove();
    }
  }
}
</script>
</head>
<body>
<div class="content pure-g">

<p class="print pure-u-1"><a href='javascript:window.print()'>Imprimir</a></p>
<?php foreach($assessments2 as $ab): ?>
  <h1 class='pure-u-1'><?php echo key($assessments2) ?></h1>
  <?php foreach($ab as $a): ?>
    <div class="spp pure-u-1">
      <div class="pure-u-1">
        <h2><i><?php echo $a["taxon"]["scientificNameWithoutAuthorship"] ?></i> <?php echo $a["taxon"]["scientificNameAuthorship"] ?></h2>
        <h3>Risco de extinção: <?php echo $a["category"]?> <?php if(isset($a['criteria'])) echo $a["criteria"] ; ?></h3>
      </div>
      <div class="pure-u-3-5">
        <p class='info'>
          Avaliador: <?php echo $a["assessor"] ?><br />
          Revisor: <?php if (isset($a["evaluator"])): echo $a["evaluator"]; endif ?><br />
          Colaboradores: <?php echo str_replace(" ;",";",$a["profile"]["metadata"]["contributor"]); ?><br />
          Data: <?php echo date('d-m-Y', $a["metadata"]["modified"] ) ?><br />
          <!-- Distribuição: TODO <br /> -->
          <!-- Bioma(s): <?php echo implode(";",$a['profile']['ecology']['biomas']) ?><br /> -->
        </p>
        <!-- Teve que ficar na mesma linha para não colocar a imagem depois da Justificativa -->
      </div>
      <p ><strong>Justificativa</strong>: <?php echo $a['rationale'] ?></p>
      <?php $img_uri = ($db == 'arvores_endemicas') ? "http://cncflora.jbrj.gov.br/arquivos/arquivos/mapas_fevereiro_2017/Novos/tt/mapas_arvores_endemicas_2018/".$a['taxon']['scientificNameWithoutAuthorship'].".jpg" : "http://cncflora.jbrj.gov.br/arquivos/arquivos/mapas_fevereiro_2017/Novos".$a['taxon']['scientificNameWithoutAuthorship'].".jpg" ?>
      <?php $img_uri2 = ($db == 'arvores_endemicas') ? "http://cncflora.jbrj.gov.br/arquivos/arquivos/mapas_fevereiro_2017/Novos/tt2/mapas_arvores_endemicas_2018/".$a['taxon']['scientificNameWithoutAuthorship'].".jpg" : "http://cncflora.jbrj.gov.br/arquivos/arquivos/mapas_fevereiro_2017/Novos".$a['taxon']['scientificNameWithoutAuthorship'].".jpg" ?>
      <?php if ( $a['category'] != "DD" ) :  ?><img src="<?php echo $img_uri ?>" class='pure-u-2-5' /><?php else: ?><img src="http://cncflora.jbrj.gov.br/arquivos/arquivos/mapas_dd_abril_17/<?php echo $a['taxon']['scientificNameWithoutAuthorship'].".jpg" ?>" class='pure-u-2-5' /> <?php endif; ?>
      <?php if ( $a['category'] != "DD" ) :  ?><img src="<?php echo $img_uri2 ?>" class='pure-u-2-5' /><?php else: ?><img src="http://cncflora.jbrj.gov.br/arquivos/arquivos/mapas_dd_abril_17/<?php echo $a['taxon']['scientificNameWithoutAuthorship'].".jpg" ?>" class='pure-u-2-5' /> <?php endif; ?>
          <hr>
        <div class="pure-u-1">
          <p class='rationale'>
            <h3>Perfil da Espécie</h3>
            Notas: <?php if (isset($a["profile"]["taxonomicNotes"]["notes"])): echo $a["profile"]["taxonomicNotes"]["notes"]; endif ?>
              <br />
              <br />
              <!-- <?php if (isset($a["profile"]["taxonomicNotes"]["references"])): foreach($a["profile"]["taxonomicNotes"]["references"] as $notesReferences) echo $notesReferences; endif ?> -->

            Economia: <?php if (isset($a["profile"]["economics"]["potentialEconomicValue"]) && $a["profile"]["economics"]["potentialEconomicValue"] != "no") :
              echo $a["profile"]["economics"]["potentialEconomicValue"]; endif ?>
              <br />
              <br />

            População:
            <br />
            <?php if (isset($a["profile"]["population"]["size"])):
            echo nl2br("Tamanho estimado => Tipo de Valor: ".$a["profile"]["population"]["size"]["type"]." Absoluto: ".$a["profile"]["population"]["size"]["absolute"]."\n");
            endif ?>

            <?php if (isset($a["profile"]["population"]["numberOfSubpopulations"])):
            echo nl2br("Número de subpopulações => Tipo de Valor: ".$a["profile"]["population"]["numberOfSubpopulations"]["type"]." Absoluto: ".$a["profile"]["population"]["numberOfSubpopulations"]["absolute"]."\n");
            endif ?>

            <?php if (isset($a["profile"]["population"]["numberOfIndividualsInBiggestSubpopulation"])):
            echo nl2br("Número de individuos na maior subpopulação => Tipo de Valor: ".$a["profile"]["population"]["numberOfIndividualsInBiggestSubpopulation"]["type"]." Absoluto: ".$a["profile"]["population"]["numberOfIndividualsInBiggestSubpopulation"]["absolute"]."\n");
            endif ?>

            <?php if (isset($a["profile"]["population"]["extremeFluctuation"])):
            echo nl2br("Flutuação extrema: ".$a["profile"]["population"]["extremeFluctuation"]["extremeFluctuation"]."\n");
            endif ?>
            <br />


            Distribuição:
            <br />
            <?php if (isset($a["profile"]["distribution"]["fragmented"])):
            echo nl2br("Fragmentada: ".$a["profile"]["distribution"]["fragmented"]."\n");
            endif ?>

            <?php if (isset($a["profile"]["distribution"]["altitude"])):
            echo nl2br("Altitude => Tipo de Valor: ".$a["profile"]["distribution"]["altitude"]["type"]." Absoluto: ".$a["profile"]["distribution"]["altitude"]["absolute"]."\n");
            endif ?>

            <?php if (isset($a["profile"]["distribution"]["brasilianEndemic"]) && $a["profile"]["distribution"]["brasilianEndemic"] == 'yes'):
            echo nl2br("Endêmica do Brasil"."\n");
            endif ?>

            <?php if (isset($a["profile"]["distribution"]["resume"])):
            echo nl2br("Resume: ".$a["profile"]["distribution"]["resume"]."\n");
            endif ?>

            <br />

            Ecologia:
            <br />
            Hábito:
            <?php if (isset($a["profile"]["ecology"]["lifeForm"])): foreach($a["profile"]["ecology"]["lifeForm"] as $lifeForm) echo nl2br($lifeForm."\n"); endif ?>
            <br />

            Substrato:
            <?php if (isset($a["profile"]["ecology"]["substratum"])): foreach($a["profile"]["ecology"]["substratum"] as $substratum) echo nl2br($substratum."\n"); endif ?>
            <br />

            Luminosidade:
            <?php if (isset($a["profile"]["ecology"]["luminosity"])): foreach($a["profile"]["ecology"]["luminosity"] as $luminosity) echo nl2br($luminosity."\n"); endif ?>
            <br />

            <?php if (isset($a["profile"]["ecology"]["longevity"])):
            echo nl2br("Longevidade: ".$a["profile"]["ecology"]["longevity"]."\n");
            endif ?>

            <?php if (isset($a["profile"]["ecology"]["fenology"])):
            echo nl2br("Fenologia: ".$a["profile"]["ecology"]["fenology"]."\n");
            endif ?>

            <?php if (isset($a["profile"]["ecology"]["clonal"])):
            echo nl2br("Crescimento Clonal: ".$a["profile"]["ecology"]["clonal"]."\n");
            endif ?>

            <?php if (isset($a["profile"]["ecology"]["resprout"])):
            echo nl2br("Rebroto: ".$a["profile"]["ecology"]["resprout"]."\n");
            endif ?>

            Hábito:
            <?php if (isset($a["profile"]["ecology"]["habitats"])): foreach($a["profile"]["ecology"]["habitats"] as $habitats) echo nl2br($habitats."\n"); endif ?>
            <br />

            Biomas:
            <?php if (isset($a["profile"]["ecology"]["biomas"])): foreach($a["profile"]["ecology"]["biomas"] as $biomas) echo nl2br($biomas."\n"); endif ?>
            <br />

            Fitofisionomias:
            <?php if (isset($a["profile"]["ecology"]["fitofisionomies"])): foreach($a["profile"]["ecology"]["fitofisionomies"] as $fitofisionomies) echo nl2br($fitofisionomies."\n"); endif ?>
            <br />

            Tipo de Vegetação:
            <?php if (isset($a["profile"]["ecology"]["vegetation"])): foreach($a["profile"]["ecology"]["vegetation"] as $vegetation) echo nl2br($vegetation."\n"); endif ?>

            <?php if (isset($a["profile"]["ecology"]["resume"])):
            echo "Resume: ".$a["profile"]["ecology"]["resume"];
            endif ?>
            <br />
            <br />

            Reprodução: <br />

            <?php if (isset($a["profile"]["reproduction"]["sexualSystem"])):
            echo nl2br("Sistema Sexual: ".$a["profile"]["reproduction"]["sexualSystem"]."\n");
            endif ?>

            <?php if (isset($a["profile"]["reproduction"]["system"])):
            echo nl2br("Sistema reprodutor: ".$a["profile"]["reproduction"]["system"]."\n");
            endif ?>

            <?php if (isset($a["profile"]["reproduction"]["strategy"])):
            echo nl2br("Estratégia de dispersão: ".$a["profile"]["reproduction"]["strategy"]."\n");
            endif ?>

            Fenologia:
            <br/>
            <?php if (isset($a["profile"]["reproduction"]["fenology"])):
              foreach($a["profile"]["reproduction"]["fenology"] as $fenology) echo nl2br($fenology["fenology"]." ".$fenology["start"]." to ".$fenology["end"]."\n");
            endif ?>

            Sindrome de polinização:
            <?php if (isset($a["profile"]["reproduction"]["polinationSyndrome"])): foreach($a["profile"]["reproduction"]["polinationSyndrome"] as $polinationSyndrome) echo nl2br($polinationSyndrome."\n"); endif ?>

            Sindrome de dispersão:
            <?php if (isset($a["profile"]["reproduction"]["dispersionSyndrome"])): foreach($a["profile"]["reproduction"]["dispersionSyndrome"] as $dispersionSyndrome) echo nl2br($dispersionSyndrome."\n"); endif ?>

            <?php if (isset($a["profile"]["reproduction"]["dispersorInformation"])):
            echo nl2br("Informações sobre o dispersor: ".$a["profile"]["reproduction"]["dispersorInformation"]."\n");
            endif ?>

            <?php if (isset($a["profile"]["reproduction"]["resume"])):
            echo nl2br("Resume: ".$a["profile"]["reproduction"]["resume"]."\n");
            endif ?>
            <br />
            <br />

            Ameaças:
            <br />
            <?php if (isset($a["profile"]["threats"]["threat"])):
            echo nl2br("Ameaça: ".$a["profile"]["threats"]["threat"]."\n");
            endif ?>

            <?php if (isset($a["profile"]["threats"]["stress"])):
            echo nl2br("Incidência: ".$a["profile"]["threats"]["stress"]."\n");
            endif ?>

            <?php if (isset($a["profile"]["threats"]["incidence"])):
            echo nl2br("Stress: ".$a["profile"]["threats"]["incidence"]."\n");
            endif ?>

            <?php if (isset($a["profile"]["threats"]["severity"])):
            echo nl2br("Severidade: ".$a["profile"]["threats"]["severity"]."\n");
            endif ?>

            <?php if (isset($a["profile"]["threats"]["reversible"])):
            echo nl2br("Reversibilidade: ".$a["profile"]["threats"]["reversible"]."\n");
            endif ?>

            Período:
            <?php if (isset($a["profile"]["threats"]["timing"])): foreach($a["profile"]["threats"]["timing"] as $timing) echo nl2br($timing."\n"); endif ?>

            Declínio:
            <?php if (isset($a["profile"]["threats"]["decline"])): foreach($a["profile"]["threats"]["decline"] as $decline) echo nl2br($decline."\n"); endif ?>

            <?php if (isset($a["profile"]["threats"]["details"])):
            echo nl2br("Detalhes: ".$a["profile"]["threats"]["details"]."\n");
            endif ?>

            Ações de Conservação: <br />

            <?php if (isset($a["profile"]["actions"])):
              foreach($a["profile"]["actions"] as $actions) echo nl2br("Ação: ".$actions["action"]."\nSituação: ".$actions["situation"]."\n".$actions["details"]."\n\n");
            endif ?>

            Usos: <br/>
            <?php if (isset($a["profile"]["uses"])):
              foreach($a["profile"]["uses"] as $uses) echo nl2br("Uso: ".(isset($uses["use"]) ? $uses["use"] : "")."\nRecurso: ".(isset($uses["resource"]) ? $uses["resource"] : "")."\nProveniência: ".(isset($uses["provenance"]) ? $uses["provenance"] : "")."\nDetalhes: ".(isset($uses["detaills"]) ? $uses["details"] : "")."\n\n");
            endif ?>
            <br />

          </p>
        </div>
    </div>
  <?php endforeach; ?>
<?php next($assessments2) ?>
<?php endforeach; ?>

<div class="refs pure-u-1">
  <h2>Referências Bibliográficas</h2>
  <ul>
  <?php foreach($references2 as $references): ?>
  <?php foreach($references as $r): ?>
    <li><?php echo htmlentities($r) ?></li>
  <?php endforeach; ?>
  <?php endforeach; ?>
  </ul>
</div>

</div>

</body>
</html>
