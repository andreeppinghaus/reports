<!DOCTYPE HTML>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Prova gráfica - <?php echo $family ?></title>
</head>
<body>
<div class="content pure-g">
  <h1><?php echo $family ?></h1>

<?php foreach($assessments as $a): ?>
  <div>
    <div>
      <h2><i><?php echo $a["taxon"]["scientificNameWithoutAuthorship"] ?></i> <?php echo $a["taxon"]["scientificNameAuthorship"] ?></h2>
      <h3>Risco de extinção: <?php echo $a["category"]?> <?php if(isset($a['criteria'])) echo $a["criteria"] ; ?></h3>
    </div>
    <div>
      <p>
        Avaliador: <?php echo $a["assessor"] ?><br />
        Revisor: <?php echo $a["evaluator"] ?><br />
        Colaboradores: <?php echo str_replace(" ;",";",$a["profile"]["metadata"]["contributor"]); ?><br />
        Data: <?php echo date('d-m-Y', $a["metadata"]["modified"] ) ?><br />
        <!-- Distribuição: TODO <br /> -->
        <!-- Bioma(s): <?php echo implode(";",$a['profile']['ecology']['biomas']) ?><br /> -->
      </p>
      <p class='rationale'><strong>Justificatica</strong>: <?php echo $a['rationale'] ?></p>
      </div>
      <?php if ( $a['category'] != "DD" ) :  ?>
      <!--
      <img 
            src="http://cncflora.jbrj.gov.br/arquivos/arquivos/mapas/<?php echo $a['taxon']['scientificNameWithoutAuthorship'].".jpg" ?>"
            class='pure-u-2-5' />
      -->
      <?php endif; ?>
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
