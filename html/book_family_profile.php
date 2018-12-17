<?php

require_once  '../vendor/autoload.php';

$html ='
<!DOCTYPE HTML>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Prova gráfica - '.$family.'</title>
  
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
  var imgs = document.querySelectorAll("img");
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
<!-- <div class="container content pure-g"> -->
  
<p class="print pure-u-1"><a href="javascript:window.print()">Imprimir</a></p>
 <h1>'.$family.'</h1>

';


/*$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($html);
$mpdf->Output();

die();
*/
foreach($assessments as $a) {
    $html .='
  <div class="spp pure-u-1">
  <h1>'.$family.'</h1>
    <div class="pure-u-1">
      <h2><i>'.$a["taxon"]["scientificNameWithoutAuthorship"].
      '</i>'.$a["taxon"]["scientificNameAuthorship"].'</h2>
      <h3>Risco de extinção: '.$a["category"];

    if(isset($a['criteria'])) {
        $html .= $a["criteria"] ; 
    }
    
    $html .='</h3>
    </div>
    <div class="pure-u-3-5">
      <p class="info">
        Avaliador: '.$a["assessor"].'<br />
        Revisor: '; 
        if (isset($a["evaluator"])){
            $html .= $a["evaluator"]; 
        } else {
            $html .= "Patrícia da Rosa";  
        }
        
        $html.='<br />
        Colaboradores:'.str_replace(" ;",";",$a["profile"]["metadata"]["contributor"]).'<br />
        Data:'.date('d-m-Y', $a["metadata"]["modified"] ).'<br />
      </p>
      <!-- Teve que ficar na mesma linha para não colocar a imagem depois da Justificativa -->
    </div>';
        
    $img_uri = ($db == 'arvores_endemicas') ? "http://cncflora.jbrj.gov.br/arquivos/arquivos/mapas_fevereiro_2017/Novos/tt/mapas_arvores_endemicas_2018/".$a['taxon']['scientificNameWithoutAuthorship'].".jpg" : "http://cncflora.jbrj.gov.br/arquivos/arquivos/mapas_fevereiro_2017/Novos".$a['taxon']['scientificNameWithoutAuthorship'].".jpg"; 
    $img_uri2 = ($db == 'arvores_endemicas') ? "http://cncflora.jbrj.gov.br/arquivos/arquivos/mapas_fevereiro_2017/Novos/tt2/".$a['taxon']['scientificNameWithoutAuthorship'].".jpg" : "http://cncflora.jbrj.gov.br/arquivos/arquivos/mapas_fevereiro_2017/Novos".$a['taxon']['scientificNameWithoutAuthorship'].".jpg" ;

    $html.= '<p>';
    $html.= '<strong>Justificativa</strong>: ';
    $html.= $a['rationale'].'</p>';

    if ( $a['category'] != "DD" || $db == 'arvores_endemicas' ) {
        
       $html .='<center><img src="'.$img_uri2.'" class="pure-u-2-5" />
                </center>';
    }else {
        $html .= '<center><img src="http://cncflora.jbrj.gov.br/arquivos/arquivos/mapas_dd_abril_17/'.
           $a['taxon']['scientificNameWithoutAuthorship'].'.jpg" class="pure-u-2-5" /></center>';
    }
    
    $html .= '<hr>
      <div class="pure-u-1">
        <p class="rationale">
     		<h3><strong>Perfil da Espécie</strong></h3>
          		 ';
      		
      		if (isset($a["profile"]["taxonomicNotes"]["notes"])) {
      		    $html .=  'Notas: '.$a["profile"]["taxonomicNotes"]["notes"];
      		    $html .= "<br /><br />";
      		}
             //<?php if (isset($a["profile"]["taxonomicNotes"]["references"])): foreach($a["profile"]["taxonomicNotes"]["references"] as $notesReferences) echo $notesReferences; endif ?

      		if ( isset($a["profile"]["economicValue"]["potentialEconomicValue"]) 
      				&& $a["profile"]["economicValue"]["potentialEconomicValue"] != "no" 
      				&& isset($a["profile"]["economicValue"]["details"])
      			) {
      			    $html .= 'Economia: ';
      			    $html .= nl2br("Espécie possui potencial valor econômico\nResume: ".$a["profile"]["economicValue"]["details"]."\n"); 
            } 
            
            if (isset($a["profile"]["economicValue"]["references"])) {
                
                foreach($a["profile"]["economicValue"]["references"] as $reference){
                    $html .= nl2br($reference."\n"); 
                }
            }

          if (isset($a["profile"]["population"]["resume"]) && $a["profile"]["population"]["resume"] != "" ) {
              $html .= nl2br($a["profile"]["population"]["resume"]."\n"); 
          }
            
          if (isset($a["profile"]["population"]["references"])) {
              foreach($a["profile"]["population"]["references"] as $reference) {
                $html .= nl2br($reference."\n"); 
              }
          }
            
            
          if (isset($a["profile"]["population"]["size"])){
              $html .="<br />".nl2br("Tamanho estimado => Tipo de Valor: ".
                  $a["profile"]["population"]["size"]["type"]." Absoluto: ".
                  $a["profile"]["population"]["size"]["absolute"]."\n");
          }

          if (isset($a["profile"]["population"]["numberOfSubpopulations"])) {
              $html .=nl2br("Número de subpopulações => Tipo de Valor: ".
                  $a["profile"]["population"]["numberOfSubpopulations"]["type"]." Absoluto: ".
                  $a["profile"]["population"]["numberOfSubpopulations"]["absolute"]."\n");
          }

          if (isset($a["profile"]["population"]["numberOfIndividualsInBiggestSubpopulation"])) {
              $html .=nl2br("Número de individuos na maior subpopulação => Tipo de Valor: ".
                  $a["profile"]["population"]["numberOfIndividualsInBiggestSubpopulation"]["type"].
                  " Absoluto: ".
                  $a["profile"]["population"]["numberOfIndividualsInBiggestSubpopulation"]["absolute"]."\n");
          }

          if (isset($a["profile"]["population"]["extremeFluctuation"])) {
              $html .= nl2br("Flutuação extrema: ".
                        $a["profile"]["population"]["extremeFluctuation"]["extremeFluctuation"]."\n");
          }
          
          if (!empty($html)) {
              $html .= "<br /><strong>População</strong>: <br /><br />";
          }
          
          
          if (isset($a["profile"]["distribution"]["fragmented"])) {
              $html .= nl2br("Fragmentada: ".$a["profile"]["distribution"]["fragmented"]."\n");
          }

         if (isset($a["profile"]["distribution"]["altitude"]) &&
             isset($a["profile"]["distribution"]["altitude"]["type"]) && 
             isset($a["profile"]["distribution"]["altitude"]["absolute"])) {
                 $html .= nl2br("Altitude => Tipo de Valor: ".$a["profile"]["distribution"]["altitude"]["type"].
                     " Absoluto: ".$a["profile"]["distribution"]["altitude"]["absolute"]."\n");
             }

          if (isset($a["profile"]["distribution"]["brasilianEndemic"]) && 
              $a["profile"]["distribution"]["brasilianEndemic"] == 'yes') {
                  $html .=nl2br("Endêmica do Brasil"."\n");
              }

          if (isset($a["profile"]["distribution"]["resume"])) {
              $html .= nl2br("Resume: ".$a["profile"]["distribution"]["resume"]."\n");
          }

          if (isset($a["profile"]["distribution"]["aoo"])) {
              $html .=nl2br("AOO: ".$a["profile"]["distribution"]["aoo"]."km²"."\n");
          }
		
          if (!empty($html)){ 
              $html .= "<br /> <strong>Distribuição:</strong> <br /><br />"; //só exibe se existir registros abaixo da distribuição 
          }
          
          if (isset($a["profile"]["distribution"]["eoo"])){
              $html .= nl2br("EOO: ".$a["profile"]["distribution"]["eoo"]."km²"."\n");
          } 
          
          if ( $a['category'] != "DD" || $db == 'arvores_endemicas' ) {
              $html .='<center><img src="'.$img_uri.'" class="pure-u-2-5" /></center>';
          } else {
              $html .='<center><img src="http://cncflora.jbrj.gov.br/arquivos/arquivos/mapas_dd_abril_17/'.
                $a['taxon']['scientificNameWithoutAuthorship'].'.jpg" class="pure-u-2-5" /></center>';
          }
          
          $html .='<br />';
           
           if (isset($a["profile"]["ecology"]["lifeForm"])) {
               $html.="<br /> Hábito: ";
               foreach($a["profile"]["ecology"]["lifeForm"] as $lifeForm) {
                   $html.= nl2br($lifeForm."\n"); 
               }
           }
            
           if (isset($a["profile"]["ecology"]["substratum"])) {
               $html .="Substrato: ";
               foreach($a["profile"]["ecology"]["substratum"] as $substratum) {
                   $html.= nl2br($substratum."\n"); 
               }
           }

           if (isset($a["profile"]["ecology"]["luminosity"])){
                $html .="Luminosidade: ";
               foreach($a["profile"]["ecology"]["luminosity"] as $luminosity){
                   $html.= nl2br($luminosity."\n"); 
               }
           }
           
           if (isset($a["profile"]["ecology"]["longevity"])) {
               $html.= nl2br("Longevidade: ".$a["profile"]["ecology"]["longevity"]."\n");
           }

           if (isset($a["profile"]["ecology"]["fenology"])) {
               $html .= nl2br("Fenologia: ".$a["profile"]["ecology"]["fenology"]."\n");
           }
            
           if (isset($a["profile"]["ecology"]["clonal"])) {
                $html .= nl2br("Crescimento Clonal: ".$a["profile"]["ecology"]["clonal"]."\n");
           }
           
           if (isset($a["profile"]["ecology"]["resprout"])) {
           $html .= nl2br("Rebroto: ".$a["profile"]["ecology"]["resprout"]."\n");
           }
           
           if (isset($a["profile"]["ecology"]["habitats"])) {
               $html .= "Habitat: ";
               foreach($a["profile"]["ecology"]["habitats"] as $habitats)  {
                   $html .= nl2br($habitats."\n");
               }
              // $html .="<br />";
           }
           
           if (isset($a["profile"]["ecology"]["biomas"])) {
               $html .= "Biomas: ";
               foreach($a["profile"]["ecology"]["biomas"] as $biomas) {
                   $html.=nl2br($biomas."\n");
               }
             //  $html .="<br />";
           }
           
           if (isset($a["profile"]["ecology"]["fitofisionomies"])) {
               $html .= "Fitofisionomias: ";
               foreach($a["profile"]["ecology"]["fitofisionomies"] as $fitofisionomies){
                   $html .= nl2br($fitofisionomies."\n");
               }
              // $html .="<br />";
           }
           
           if (isset($a["profile"]["ecology"]["vegetation"])) {
               $html .="Tipo de Vegetação: ";
               foreach($a["profile"]["ecology"]["vegetation"] as $vegetation){
                $html .= nl2br($vegetation."\n");
               }
           }    
           
           if (isset($a["profile"]["ecology"]["resume"])) {
               $html .="Resume: ".$a["profile"]["ecology"]["resume"];
           }
           
           if (!empty($html)) {
               $html .= " <br /><strong>Ecologia: </strong><br /><br />";//só exibe se existir registros abaixo da Ecologia
           }
           
           if (isset($a["profile"]["reproduction"]["sexualSystem"])){
               $html.=nl2br("Sistema Sexual: ".$a["profile"]["reproduction"]["sexualSystem"]."\n");
           }
           
           if (isset($a["profile"]["reproduction"]["system"])) {
               $html.=nl2br("Sistema reprodutor: ".$a["profile"]["reproduction"]["system"]."\n");
           }
           
           if (isset($a["profile"]["reproduction"]["strategy"])) {
               $html.=nl2br("Estratégia de dispersão: ".$a["profile"]["reproduction"]["strategy"]."\n");
           }
           
           if (isset($a["profile"]["reproduction"]["fenology"])) {
               $html .= "Fenologia: <br/>";
               foreach($a["profile"]["reproduction"]["fenology"] as $fenology) {
                   $html .= nl2br($fenology["fenology"]." ".$fenology["start"]." to ".$fenology["end"]."\n");
               }
           }
           
           if (isset($a["profile"]["reproduction"]["polinationSyndrome"])) {
               $html .="Sindrome de polinização:";
               foreach($a["profile"]["reproduction"]["polinationSyndrome"] as $polinationSyndrome){
                   $html .= nl2br($polinationSyndrome."\n");
               }
           }
           
           $html .="Sindrome de dispersão:";
           if (isset($a["profile"]["reproduction"]["dispersionSyndrome"])){
               foreach($a["profile"]["reproduction"]["dispersionSyndrome"] as $dispersionSyndrome) {
                   $html .= nl2br($dispersionSyndrome."\n");
               }
           }
           
           if (isset($a["profile"]["reproduction"]["dispersorInformation"])) {
               $html .= nl2br("Informações sobre o dispersor: ".$a["profile"]["reproduction"]["dispersorInformation"]."\n");
           }
           
           if (isset($a["profile"]["reproduction"]["resume"])) {
               $html .= nl2br("Resume: ".$a["profile"]["reproduction"]["resume"]."\n");
           }
           if (!empty($html)) {
               $html .= "<br /><br /><strong>Reprodução:</strong> <br /><br />";
           }
           
           if (isset($a["profile"]["threats"]) && is_array($a["profile"]["threats"])){
               
               foreach ($a["profile"]["threats"] as $threat) {
                   $html .= "<br>";//novo
                   if (isset($threat["threat"])) {
                       $html .=nl2br("Ameaça: ".$threat["threat"]."\n");
                   }
                   
                   if (isset($threat["stress"])) {
                       $html .=nl2br("Incidência: ".$threat["stress"]."\n");
                   }
                   
                   if (isset($threat["incidence"])) {
                       $html .= nl2br("Stress: ".$threat["incidence"]."\n");
                   }
                   
                   if (isset($threat["severity"])) {
                       $html .= nl2br("Severidade: ".$threat["severity"]."\n");
                   }
                   
                   if (isset($threat["reversible"])) {
                       $html .= nl2br("Reversibilidade: ".$threat["reversible"]."\n");
                   }
                   
                   $html .= nl2br("Período:\n");
                   if (isset($threat["timing"])) {
                       foreach($threat["timing"] as $timing) {
                           $html .= nl2br($timing."\n");
                       }
                   }
                   
                   $html .= nl2br("Declínio:\n");
                   if (isset($threat["decline"])) {
                       foreach($threat["decline"] as $decline) {
                           $html.= nl2br($decline."\n");
                       }
                   }
                   
                   if (isset($threat["details"])) {
                       $html.=nl2br("Detalhes: ".$threat["details"]."\n");
                   }
                   
               }//fim foreach
               
           }//fim if
           
           if (!empty($html)){
               $html .= "<br /><br /><strong>Ameaças:</strong><br />";
               
           }
           
           if (isset($a["profile"]["actions"])){
               foreach($a["profile"]["actions"] as $actions) {
                   if(isset($actions["action"]) && isset($actions["situation"]) && isset($actions["details"])) {
                       $html .=nl2br("Ação: ".$actions["action"]."\nSituação: ".$actions["situation"]."\n".$actions["details"]."\n\n");
                   }//fim if
               }//fim foreach
           }//fim if
           
           if (!empty($html)){
               $html .= "<br /><strong> Ações de Conservação:</strong> <br /><br />";
               $html .= $html;
           }
           
           if (isset($a["profile"]["uses"])) {
               foreach($a["profile"]["uses"] as $uses) {
                   $html .= nl2br("Uso: ".(isset($uses["use"]) ? $uses["use"] : "").
                       "\nRecurso: ".(isset($uses["resource"]) ? $uses["resource"] : "").
                       "\nProveniência: ".(isset($uses["provenance"]) ? $uses["provenance"] : "").
                       "\nDetalhes: ".(isset($uses["detaills"]) ? $uses["details"] : "")."\n\n");
               }
           }
           if (! empty($html)) {
               $html .= " Usos: <br/>";
               $html .= $html;
           }

           $html .='        </p>
        </div>
  </div>';

}//fim foreach

echo $html;
?>

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
