<?php

// img {
//             border-bottom-style: none;
//             border-bottom-width:0px;
//             border-image-outset:0;
//             border-image-repeat:stretch;
//             border-image-slice:100%;
//             border-image-source:none;
//             border-image-width: 1;
//             display:inline-block;
//             width:40%;
    //      }
    
require_once  '../vendor/autoload.php';

$html ='
<!DOCTYPE HTML>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Prova gráfica - '.$family.'</title>
  
  <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/pure-min.css">
  <style type="text/css">

//     
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

.img {
         height: 410px;
         width:260px;
         background-position: 50% 50%;
         background-repeat: no-repeat;
         background-size: cover;
         background-color: green;
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
';


/*$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($html);
$mpdf->Output();

die();
*/
$html2='';
foreach($assessments as $a) {
    $html .='
  <div class="spp pure-u-1">
  <h1 class="pure-u-1">'.$family.'</h1>
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
        $html .= '<center>

              <div>
              <img src="http://cncflora.jbrj.gov.br/arquivos/arquivos/mapas_dd_abril_17/'.
           $a['taxon']['scientificNameWithoutAuthorship'].'.jpg"                

        style="
         height: auto ;
         width:410px;
display:block;
margin:0 auto;
         "
                 />
         
              </div> 
             </center>';
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

          $html2='';
          if (isset($a["profile"]["population"]["resume"]) && $a["profile"]["population"]["resume"] != "" ) {
              $html2 .= nl2br($a["profile"]["population"]["resume"]."\n"); 
          }
            
          if (isset($a["profile"]["population"]["references"])) {
              foreach($a["profile"]["population"]["references"] as $reference) {
                $html2 .= nl2br($reference."\n"); 
              }
          }
            
            
          if (isset($a["profile"]["population"]["size"])){
              $html2 .="<br />".nl2br("Tamanho estimado => Tipo de Valor: ".
                  $a["profile"]["population"]["size"]["type"]." Absoluto: ".
                  $a["profile"]["population"]["size"]["absolute"]."\n");
          }

          if (isset($a["profile"]["population"]["numberOfSubpopulations"])) {
              $html2 .=nl2br("Número de subpopulações => Tipo de Valor: ".
                  $a["profile"]["population"]["numberOfSubpopulations"]["type"]." Absoluto: ".
                  $a["profile"]["population"]["numberOfSubpopulations"]["absolute"]."\n");
          }

          if (isset($a["profile"]["population"]["numberOfIndividualsInBiggestSubpopulation"])) {
              $html2 .=nl2br("Número de individuos na maior subpopulação => Tipo de Valor: ".
                  $a["profile"]["population"]["numberOfIndividualsInBiggestSubpopulation"]["type"].
                  " Absoluto: ".
                  $a["profile"]["population"]["numberOfIndividualsInBiggestSubpopulation"]["absolute"]."\n");
          }

          if (isset($a["profile"]["population"]["extremeFluctuation"])) {
              $html2 .= nl2br("Flutuação extrema: ".
                        $a["profile"]["population"]["extremeFluctuation"]["extremeFluctuation"]."\n");
          }
          
          if (!empty($html2)) {
              $html .= $html2 ."<br /><strong>População</strong>: <br/>";
              $html2='';
          }
          
          $html2='';
          
          if (isset($a["profile"]["distribution"]["fragmented"])) {
              $html2 .= nl2br("Fragmentada: ".$a["profile"]["distribution"]["fragmented"]."\n");
          }

         if (isset($a["profile"]["distribution"]["altitude"]) &&
             isset($a["profile"]["distribution"]["altitude"]["type"]) && 
             isset($a["profile"]["distribution"]["altitude"]["absolute"])) {
                 $html2 .= nl2br("Altitude => Tipo de Valor: ".$a["profile"]["distribution"]["altitude"]["type"].
                     " Absoluto: ".$a["profile"]["distribution"]["altitude"]["absolute"]."\n");
             }

          if (isset($a["profile"]["distribution"]["brasilianEndemic"]) && 
              $a["profile"]["distribution"]["brasilianEndemic"] == 'yes') {
                  $html2 .=nl2br("Endêmica do Brasil"."\n");
              }

          if (isset($a["profile"]["distribution"]["resume"])) {
              $html2 .= nl2br("Resume: ".$a["profile"]["distribution"]["resume"]."\n");
          }

          if (isset($a["profile"]["distribution"]["aoo"])) {
              $html2 .=nl2br("AOO: ".$a["profile"]["distribution"]["aoo"]."km²"."\n");
          }
		
          if (!empty($html2)){ 
              $html .= $html2."<br /> <strong>Distribuição:</strong> <br />"; //só exibe se existir registros abaixo da distribuição
              $html2='';
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
               $html2 .="<br /> Hábito: ";
               foreach($a["profile"]["ecology"]["lifeForm"] as $lifeForm) {
                   $html2 .= nl2br($lifeForm."\n"); 
               }
           }
            
           if (isset($a["profile"]["ecology"]["substratum"])) {
               $html2 .="Substrato: ";
               foreach($a["profile"]["ecology"]["substratum"] as $substratum) {
                   $html2 .= nl2br($substratum."\n"); 
               }
           }

           if (isset($a["profile"]["ecology"]["luminosity"])){
                $html2 .="Luminosidade: ";
               foreach($a["profile"]["ecology"]["luminosity"] as $luminosity){
                   $html2.= nl2br($luminosity."\n"); 
               }
           }
           
           if (isset($a["profile"]["ecology"]["longevity"])) {
               $html2 .= nl2br("Longevidade: ".$a["profile"]["ecology"]["longevity"]."\n");
           }

           if (isset($a["profile"]["ecology"]["fenology"])) {
               $html2 .= nl2br("Fenologia: ".$a["profile"]["ecology"]["fenology"]."\n");
           }
            
           if (isset($a["profile"]["ecology"]["clonal"])) {
                $html2 .= nl2br("Crescimento Clonal: ".$a["profile"]["ecology"]["clonal"]."\n");
           }
           
           if (isset($a["profile"]["ecology"]["resprout"])) {
           $html2 .= nl2br("Rebroto: ".$a["profile"]["ecology"]["resprout"]."\n");
           }
           
           if (isset($a["profile"]["ecology"]["habitats"])) {
               $html2 .= "Habitat: ";
               foreach($a["profile"]["ecology"]["habitats"] as $habitats)  {
                   $html2 .= nl2br($habitats."\n");
               }
              // $html .="<br />";
           }
           
           if (isset($a["profile"]["ecology"]["biomas"])) {
               $html2 .= "Biomas: ";
               foreach($a["profile"]["ecology"]["biomas"] as $biomas) {
                   $html2.=nl2br($biomas."\n");
               }
             //  $html .="<br />";
           }
           
           if (isset($a["profile"]["ecology"]["fitofisionomies"])) {
               $html2 .= "Fitofisionomias: ";
               foreach($a["profile"]["ecology"]["fitofisionomies"] as $fitofisionomies){
                   $html2 .= nl2br($fitofisionomies."\n");
               }
              // $html .="<br />";
           }
           
           if (isset($a["profile"]["ecology"]["vegetation"])) {
               $html2 .="Tipo de Vegetação: ";
               foreach($a["profile"]["ecology"]["vegetation"] as $vegetation){
                $html2 .= nl2br($vegetation."\n");
               }
           }    
           
           if (isset($a["profile"]["ecology"]["resume"])) {
               $html2 .="Resume: ".$a["profile"]["ecology"]["resume"];
           }
           
           if (!empty($html2)) {
               $html .= $html2." <br /><strong>Ecologia: </strong><br />";//só exibe se existir registros abaixo da Ecologia
               $html2='';
           }
           
           
           
           if (isset($a["profile"]["reproduction"]["sexualSystem"])){
               $html2 .=nl2br("Sistema Sexual: ".$a["profile"]["reproduction"]["sexualSystem"]."\n");
           }
           
           if (isset($a["profile"]["reproduction"]["system"])) {
               $html2 .=nl2br("Sistema reprodutor: ".$a["profile"]["reproduction"]["system"]."\n");
           }
           
           if (isset($a["profile"]["reproduction"]["strategy"])) {
               $html2 .=nl2br("Estratégia de dispersão: ".$a["profile"]["reproduction"]["strategy"]."\n");
           }
           
           if (isset($a["profile"]["reproduction"]["fenology"])) {
               $html2 .= "Fenologia: <br/>";
               foreach($a["profile"]["reproduction"]["fenology"] as $fenology) {
                   $html2 .= nl2br($fenology["fenology"]." ".$fenology["start"]." to ".$fenology["end"]."\n");
               }
           }
           
           if (isset($a["profile"]["reproduction"]["polinationSyndrome"])) {
               $html2 .="Sindrome de polinização:";
               foreach($a["profile"]["reproduction"]["polinationSyndrome"] as $polinationSyndrome){
                   $html2 .= nl2br($polinationSyndrome."\n");
               }
           }
           
           $html2 .="Sindrome de dispersão:";
           if (isset($a["profile"]["reproduction"]["dispersionSyndrome"])){
               foreach($a["profile"]["reproduction"]["dispersionSyndrome"] as $dispersionSyndrome) {
                   $html2 .= nl2br($dispersionSyndrome."\n");
               }
           }
           
           if (isset($a["profile"]["reproduction"]["dispersorInformation"])) {
               $html2 .= nl2br("Informações sobre o dispersor: ".$a["profile"]["reproduction"]["dispersorInformation"]."\n");
           }
           
           if (isset($a["profile"]["reproduction"]["resume"])) {
               $html2 .= nl2br("Resume: ".$a["profile"]["reproduction"]["resume"]."\n");
           }
           if (!empty($html2)) {
               $html .=$html2. "<br /><br /><strong>Reprodução:</strong> <br />";
               $html2='';
           }
           
           if (isset($a["profile"]["threats"]) && is_array($a["profile"]["threats"])){
               
               foreach ($a["profile"]["threats"] as $threat) {
                   $html2 .= "<br>";//novo
                   if (isset($threat["threat"])) {
                       $html2 .=nl2br("Ameaça: ".$threat["threat"]."\n");
                   }
                   
                   if (isset($threat["stress"])) {
                       $html2 .=nl2br("Incidência: ".$threat["stress"]."\n");
                   }
                   
                   if (isset($threat["incidence"])) {
                       $html2 .= nl2br("Stress: ".$threat["incidence"]."\n");
                   }
                   
                   if (isset($threat["severity"])) {
                       $html2 .= nl2br("Severidade: ".$threat["severity"]."\n");
                   }
                   
                   if (isset($threat["reversible"])) {
                       $html2 .= nl2br("Reversibilidade: ".$threat["reversible"]."\n");
                   }
                   
                   if (isset($threat["timing"])) {
                       $html2 .= nl2br("Período:\n");
                       foreach($threat["timing"] as $timing) {
                           $html2 .= nl2br($timing."\n");
                       }
                   }
                   
                   
                   if (isset($threat["decline"])) {
                       $html2 .= nl2br("Declínio:\n");
                       foreach($threat["decline"] as $decline) {
                           $html2 .= nl2br($decline."\n");
                       }
                   }
                   
                   if (isset($threat["details"])) {
                       $html2.=nl2br("Detalhes: ".$threat["details"]."\n");
                   }
                   
               }//fim foreach
               
           }//fim if
           
           if (!empty($html2)){
               $html .= $html2."<br /><br /><strong>Ameaças:</strong><br />";
               $html2='';
           }
           
           if (isset($a["profile"]["actions"])){
               foreach($a["profile"]["actions"] as $actions) {
                   if(isset($actions["action"]) && isset($actions["situation"]) && isset($actions["details"])) {
                       $html2 .=nl2br("Ação: ".$actions["action"]."\nSituação: ".$actions["situation"]."\n".$actions["details"]."\n\n");
                   }//fim if
               }//fim foreach
           }//fim if
           
           if (!empty($html2)){
               $html .= $html2."<br /><strong> Ações de Conservação:</strong> <br />";
               $html2='';
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
           }

           $html .='        </p>
        </div>
  </div>';

}//fim foreach

// Create an instance of the class:


$html.='
<div class="refs pure-u-1">
  <h2>Referências Bibliográficas</h2>
  <ul>';
foreach($references as $r) {
   $html .='<li>'.htmlentities($r).'</li>';
}
$html .='</ul>
</div>

';

$mpdf = new \Mpdf\Mpdf();

// Write some HTML code:
$mpdf->WriteHTML($html);

// Output a PDF file directly to the browser
$mpdf->Output();

die();

// echo $html;
