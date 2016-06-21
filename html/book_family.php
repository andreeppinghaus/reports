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
      <p class='rationale'><strong>Justificatica</strong>: <?php echo $a['rationale'] ?></p>
      </div><?php if ( $a['category'] != "DD" ) :  ?><img 
      src="http://cncflora.jbrj.gov.br/arquivos/arquivos/mapas/<?php echo $a['taxon']['scientificNameWithoutAuthorship'].".jpg" ?>"
      class='pure-u-2-5' /><?php endif; ?>
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
