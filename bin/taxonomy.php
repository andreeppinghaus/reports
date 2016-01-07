<?php

global $title, $description, $is_private, $fields;
$title = "Mudanças taxonômicas";
$description = "Lista com todas as mudanças taxonômicas.";
$is_private = false;
$fields = ["família", "espécie", "nome científico", "status", "changed"];
include 'base.php';
include 'utils/get_taxon.php';

fputcsv($csv,$fields);

$curl = curl_init();
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$url_base = 'http://cncflora.jbrj.gov.br:80/floradata/api/v1/specie?scientificName=';

$taxons = get_taxon_accepted(ELASTICSEARCH, $base);
foreach($taxons as $taxon){
    $synonyms_db = [];
    $synonyms_flora = [];
    $synonyms = get_taxon_synonyms(ELASTICSEARCH, $base, $taxon->scientificNameWithoutAuthorship);
    foreach($synonyms as $synonym){
        array_push($synonyms_db, strtolower($synonym->scientificNameWithoutAuthorship));
    }
    $url = $url_base.urlencode($taxon->acceptedNameUsage);
    curl_setopt($curl, CURLOPT_URL, $url);
    $result = curl_exec($curl);
    $result = json_decode($result);

    if (empty($result->result)) {
        $data = [strtoupper($taxon->family), $taxon->acceptedNameUsage,
            $taxon->acceptedNameUsage, $taxon->taxonomicStatus, "not_found"];
        fputcsv($csv,$data);
    } else {
        if (strtolower($taxon->scientificNameWithoutAuthorship) != strtolower($result->result->scientificNameWithoutAuthorship)) {
            $data = [strtoupper($taxon->family), $result->result->acceptedNameUsage, $taxon->acceptedNameUsage,
                $taxon->taxonomicStatus, "taxonomy_changed"];
            fputcsv($csv,$data);
        }

        foreach($result->result->synonyms as $synonym) {
            if ($synonym->taxonomicStatus == 'synonym') {
                 array_push($synonyms_flora, strtolower($synonym->scientificNameWithoutAuthorship));
            }
        }
        $syn_extra = array_diff($synonyms_flora, $synonyms_db);
        foreach($syn_extra as $synonym) {
            $data = [strtoupper($taxon->family), $taxon->acceptedNameUsage, $synonym, "synonym", "synonym_added"];
            fputcsv($csv,$data);
        }
        $syn_extra = array_diff($synonyms_db, $synonyms_flora);
        foreach($syn_extra as $synonym) {
            $data = [strtoupper($taxon->family), $taxon->acceptedNameUsage, $synonym, "synonym", "synonym_removed"];
            fputcsv($csv,$data);
        }
    }
}
