<!DOCTYPE HTML>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Prova gráfica - <?php echo $family ?></title>
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
  <h1 class='pure-u-1'><?php echo $family ?></h1>
<p class="print pure-u-1"><a href='javascript:window.print()'>Imprimir</a></p>

<?php foreach($assessments as $a): ?>
  <div class="spp pure-u-1">
    <div class="pure-u-1">
      <h2><i><?php echo $a["taxon"]["scientificNameWithoutAuthorship"] ?></i> <?php echo $a["taxon"]["scientificNameAuthorship"] ?></h2>
      <h3>Risco de extinção: <?php echo $a["category"]?> <?php if(isset($a['criteria'])) echo $a["criteria"] ; ?></h3>
    </div>
    <div class="pure-u-3-5">
      <p class='info'>
        Avaliador: <?php echo $a["assessor"] ?><br />
        Revisor: <?php echo $a["evaluator"] ?><br />
        Colaboradores: <?php echo str_replace(" ;",";",$a["profile"]["metadata"]["contributor"]); ?><br />
        Data: <?php echo date('d-m-Y', $a["metadata"]["modified"] ) ?><br />
        <!-- Distribuição: TODO <br /> -->
        <!-- Bioma(s): <?php echo implode(";",$a['profile']['ecology']['biomas']) ?><br /> -->
      </p>
      <p class='rationale'><strong>Justificativa</strong>: <?php echo $a['rationale'] ?></p>
      <!-- Teve que ficar na mesma linha para não colocar a imagem depois da Justificativa -->
      <?php $img_uri = ($db == 'arvores_endemicas') ? "http://cncflora.jbrj.gov.br/arquivos/arquivos/mapas_arvores_endemicas_2018/" : "http://cncflora.jbrj.gov.br/arquivos/arquivos/mapas_fevereiro_2017/Novos" ?>
    </div><?php if ( $a['category'] != "DD" ) :  ?><img src="<?php echo $img_uri.$a['taxon']['scientificNameWithoutAuthorship'].".jpg" ?>" class='pure-u-2-5' /><?php else: ?><img src="http://cncflora.jbrj.gov.br/arquivos/arquivos/mapas_dd_abril_17/<?php echo $a['taxon']['scientificNameWithoutAuthorship'].".jpg" ?>" class='pure-u-2-5' /> <?php endif; ?>
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
          echo "Tamanho estimado => Tipo de Valor: ".$a["profile"]["population"]["size"]["type"]." Absoluto: ".$a["profile"]["population"]["size"]["absolute"];
          endif ?>
          <br />

          <?php if (isset($a["profile"]["population"]["numberOfSubpopulations"])):
          echo "Número de subpopulações => Tipo de Valor: ".$a["profile"]["population"]["numberOfSubpopulations"]["type"]." Absoluto: ".$a["profile"]["population"]["numberOfSubpopulations"]["absolute"];
          endif ?>
          <br />

          <?php if (isset($a["profile"]["population"]["numberOfIndividualsInBiggestSubpopulation"])):
          echo "Número de individuos na maior subpopulação => Tipo de Valor: ".$a["profile"]["population"]["numberOfIndividualsInBiggestSubpopulation"]["type"]." Absoluto: ".$a["profile"]["population"]["numberOfIndividualsInBiggestSubpopulation"]["absolute"];
          endif ?>
          <br />

          <?php if (isset($a["profile"]["population"]["extremeFluctuation"])):
          echo "Flutuação extrema: ".$a["profile"]["population"]["extremeFluctuation"]["extremeFluctuation"];
          endif ?>
          <br />
          <br />


          Distribuição:
          <br />
          <?php if (isset($a["profile"]["distribution"]["fragmented"])):
          echo "Fragmentada: ".$a["profile"]["distribution"]["fragmented"];
          endif ?>
          <br />

          <?php if (isset($a["profile"]["distribution"]["altitude"])):
          echo "Altitude => Tipo de Valor: ".$a["profile"]["distribution"]["altitude"]["type"]." Absoluto: ".$a["profile"]["distribution"]["altitude"]["absolute"];
          endif ?>
          <br />

          <?php if (isset($a["profile"]["distribution"]["brasilianEndemic"]) && $a["profile"]["distribution"]["brasilianEndemic"] == 'yes'):
          echo "Endêmica do Brasil" ;
          endif ?>
          <br />

          <?php if (isset($a["profile"]["distribution"]["resume"])):
          echo "Resume: ".$a["profile"]["distribution"]["resume"];
          endif ?>
          <br />
          <br />

          Ecologia:
          <br />
          Hábito:
          <?php if (isset($a["profile"]["ecology"]["lifeForm"])): foreach($a["profile"]["ecology"]["lifeForm"] as $lifeForm) echo $lifeForm; endif ?>
          <br />

          Substrato:
          <?php if (isset($a["profile"]["ecology"]["substratum"])): foreach($a["profile"]["ecology"]["substratum"] as $substratum) echo $substratum; endif ?>
          <br />

          Luminosidade:
          <?php if (isset($a["profile"]["ecology"]["luminosity"])): foreach($a["profile"]["ecology"]["luminosity"] as $luminosity) echo $luminosity; endif ?>
          <br />

          <?php if (isset($a["profile"]["ecology"]["longevity"])):
          echo "Longevidade: ".$a["profile"]["ecology"]["longevity"];
          endif ?>
          <br />

          <?php if (isset($a["profile"]["ecology"]["longevity"])):
          echo "Longevidade: ".$a["profile"]["ecology"]["longevity"];
          endif ?>
          <br />

          <?php if (isset($a["profile"]["ecology"]["fenology"])):
          echo "Fenologia: ".$a["profile"]["ecology"]["fenology"];
          endif ?>
          <br />

          <?php if (isset($a["profile"]["ecology"]["clonal"])):
          echo "Crescimento Clonal: ".$a["profile"]["ecology"]["clonal"];
          endif ?>
          <br />

          <?php if (isset($a["profile"]["ecology"]["resprout"])):
          echo "Rebroto: ".$a["profile"]["ecology"]["resprout"];
          endif ?>
          <br />

          Hábito:
          <?php if (isset($a["profile"]["ecology"]["habitats"])): foreach($a["profile"]["ecology"]["habitats"] as $habitats) echo $habitats; endif ?>
          <br />

          Biomas:
          <?php if (isset($a["profile"]["ecology"]["biomas"])): foreach($a["profile"]["ecology"]["biomas"] as $biomas) echo $biomas; endif ?>
          <br />

          Fitofisionomias:
          <?php if (isset($a["profile"]["ecology"]["fitofisionomies"])): foreach($a["profile"]["ecology"]["fitofisionomies"] as $fitofisionomies) echo $fitofisionomies; endif ?>
          <br />

          Tipo de Vegetação:
          <?php if (isset($a["profile"]["ecology"]["vegetation"])): foreach($a["profile"]["ecology"]["vegetation"] as $vegetation) echo $vegetation; endif ?>
          <br />

          <?php if (isset($a["profile"]["ecology"]["resume"])):
          echo "Resume: ".$a["profile"]["ecology"]["resume"];
          endif ?>
          <br />
          <br />

          Reprodução: <br />

          <?php if (isset($a["profile"]["reproduction"]["sexualSystem"])):
          echo "Sistema Sexual: ".$a["profile"]["reproduction"]["sexualSystem"];
          endif ?>
          <br />

          <?php if (isset($a["profile"]["reproduction"]["system"])):
          echo "Sistema reprodutor: ".$a["profile"]["reproduction"]["system"];
          endif ?>
          <br />

          <?php if (isset($a["profile"]["reproduction"]["strategy"])):
          echo "Estratégia de dispersão: ".$a["profile"]["reproduction"]["strategy"];
          endif ?>
          <br />

          Fenologia:
          <br/>
          <?php if (isset($a["profile"]["reproduction"]["fenology"])):
            foreach($a["profile"]["reproduction"]["fenology"] as $fenology) echo nl2br($fenology["fenology"]." ".$fenology["start"]." to ".$fenology["end"]."\n");
          endif ?>
          <br />

          Sindrome de polinização:
          <?php if (isset($a["profile"]["reproduction"]["polinationSyndrome"])): foreach($a["profile"]["reproduction"]["polinationSyndrome"] as $polinationSyndrome) echo $polinationSyndrome; endif ?>
          <br />

          Sindrome de dispersão:
          <?php if (isset($a["profile"]["reproduction"]["dispersionSyndrome"])): foreach($a["profile"]["reproduction"]["dispersionSyndrome"] as $dispersionSyndrome) echo $dispersionSyndrome; endif ?>
          <br />

          <?php if (isset($a["profile"]["reproduction"]["dispersorInformation"])):
          echo "Informações sobre o dispersor: ".$a["profile"]["reproduction"]["dispersorInformation"];
          endif ?>
          <br />

          <?php if (isset($a["profile"]["reproduction"]["resume"])):
          echo "Resume: ".$a["profile"]["reproduction"]["resume"];
          endif ?>
          <br />
          <br />

          Ameaças:
          <br />
          <?php if (isset($a["profile"]["threats"]["threat"])):
          echo "Ameaça: ".$a["profile"]["threats"]["threat"];
          endif ?>

          <?php if (isset($a["profile"]["threats"]["stress"])):
          echo "Incidência: ".$a["profile"]["threats"]["stress"];
          endif ?>

          <?php if (isset($a["profile"]["threats"]["incidence"])):
          echo "Stress: ".$a["profile"]["threats"]["incidence"];
          endif ?>

          <?php if (isset($a["profile"]["threats"]["severity"])):
          echo "Severidade: ".$a["profile"]["threats"]["severity"];
          endif ?>

          <?php if (isset($a["profile"]["threats"]["reversible"])):
          echo "Reversibilidade: ".$a["profile"]["threats"]["reversible"];
          endif ?>

          Período:
          <?php if (isset($a["profile"]["threats"]["timing"])): foreach($a["profile"]["threats"]["timing"] as $timing) echo $timing; endif ?>
          <br />

          Declínio:
          <?php if (isset($a["profile"]["threats"]["decline"])): foreach($a["profile"]["threats"]["decline"] as $decline) echo $decline; endif ?>
          <br />

          <?php if (isset($a["profile"]["threats"]["details"])):
          echo "Detalhes: ".$a["profile"]["threats"]["details"];
          endif ?>

          Ações de Conservação: <br />

          <?php if (isset($a["profile"]["actions"])):
            foreach($a["profile"]["actions"] as $actions) echo nl2br("Ação: ".$actions["action"]."\nSituação: ".$actions["situation"]."\n".$actions["details"]."\n\n");
          endif ?>
          <br />

          Usos: <br
          <?php if (isset($a["profile"]["uses"])):
            foreach($a["profile"]["uses"] as $uses) echo nl2br("Uso: ".(isset($uses["use"]) ? $uses["use"] : "")."\nRecurso: ".(isset($uses["resource"]) ? $uses["resource"] : "")."\nProveniência: ".(isset($uses["provenance"]) ? $uses["provenance"] : "")."\nDetalhes: ".(isset($uses["detaills"]) ? $uses["details"] : "")."\n\n");
          endif ?>
          <br />

        </p>
      </div>
  </div>
<?php endforeach; ?>

<div class="refs pure-u-1">
  <h2>Referências Bibliográficas</h2>
  <ul>
  <?php foreach($references as $r): ?>
    <li><?php echo htmlentities($r) ?></li>
  <?php endforeach; ?>
  </ul>
</div>

</div>

</body>
</html>
