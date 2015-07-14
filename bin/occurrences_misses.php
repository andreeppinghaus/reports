<?php
return;

$taxonsCSV = fopen('data/plantas_raras_do_cerrado/status.csv','r');

$taxons = [];
$headers = fgetcsv($taxonsCSV);
while($taxonRow = fgetcsv($taxonsCSV)) {
  $taxon = new StdClass;
  foreach($headers as $column=>$header){
    $taxon->$header  = $taxonRow[$column];
  }

  $taxon->occurrences = 0;
  $taxon->validated = 0;
  $taxon->non_validated = 0;

  $taxons[$taxon->scientificNameWithoutAuthorship." ".$taxon->scientificNameAuthorship] = $taxon;
}
fclose($taxonsCSV);

$occsCSV = fopen('data/plantas_raras_do_cerrado/occurrences.csv','r');
$headers = fgetcsv($occsCSV);
while($occRow = fgetcsv($occsCSV)) {
  $occ = new StdClass;
  foreach($headers as $column=>$header){
    $occ->$header  = $occRow[$column];
  }
  if(!isset($taxons[$occ->acceptedNameUsage])) continue;
  $taxon = $taxons[$occ->acceptedNameUsage];

  $taxon->occurrences++;
  if($occ->valid === 'true') {
    $taxon->validated++;
  } else if($occ->valid === 'false') {
    $taxon->validated++;
  } else {
    $taxon->non_validated++;
  }
}
fclose($occsCSV);


$result = fopen('miss.csv','w');
$headers = ['family','scientificNameWithoutAuthorship',"scientificNameAuthorship","category","occurrences",'validated','non_validated'];
fputcsv($result,$headers);
foreach($taxons as $taxon) {
  $data = [];
  foreach($headers as $h) {
    $data[] = $taxon->$h;
  }
  fputcsv($result,$data);
}
fclose($result);



