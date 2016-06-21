<?php

namespace cncflora\repository;

class Assessment {


  public $couchdb = null;
  public $db = null;
  public $elasticsearch=null;

  public function __construct($db) {
    $this->db=$db;
    $this->couchdb = \cncflora\Config::couchdb($db);
    $this->elasticsearch = \cncflora\Config::elasticsearch();
  }

  public function listAll() {
    $taxonRepo = new Taxon($this->db);
    $families = $taxonRepo->listFamilies();
    $names= [];
    foreach($families as $f) {
      $spps = $taxonRepo->listFamily($f);
      array_merge($names,$spps);
    }

    $params=[
      'index'=>$this->db,
      'type'=>'assessment',
      'body'=>[
        'size'=> 9999,
        'query'=>[
          'bool'=>[
            'should'=>[ ]
          ]
        ]
      ]
    ];

    foreach($names as $name) {
      $params['body']['query']['bool']['should'][]
        = ['match'=>['scientificNameWithoutAuthorship'=>['query'=>$name,'operator'=>'and']]];
    }

    $assessments=[];
    $result = $this->elasticsearch->search($params);
    foreach($result['hits']['hits'] as $hit) {
      $assessments[]=$hit['_source'];
    }

    usort($assessments,function($a0,$a1){
      return $a0["metadata"]["modified"] > $a1["metadata"]["modified"];
    });

    $got=[];
    foreach($assessments as $p) {
      $name = $p['taxon']['scientificNameWithoutAuthorship'];
      if(isset($got[$name])) continue;
      else $got[$name]=true;
    }

    usort($assessments,function($a,$b) { 
      return strcmp($a['taxon']['family']." ".$a['taxon']['scientificNameWithoutAuthorship']
                   ,$b['taxon']['family']." ".$b['taxon']['scientificNameWithoutAuthorship']);
    });

    return $assessments;
  }

  public function listFamily($f) {
    $names=[];

    $taxonRepo = new Taxon($this->db);
    $spps = $taxonRepo->listFamily($f);
    foreach($spps as $s) {
      $names[] = $s['scientificNameWithoutAuthorship'];
    }

    $params=[
      'index'=>$this->db,
      'type'=>'assessment',
      'body'=>[
        'size'=> 9999,
        'query'=>[
          'bool'=>[
            'should'=>[ ]
          ]
        ]
      ]
    ];

    foreach($names as $name) {
      $params['body']['query']['bool']['should'][]
        = ['match'=>['scientificNameWithoutAuthorship'=>['query'=>$name,'operator'=>'and']]];
    }

    $assessments=[];
    $result = $this->elasticsearch->search($params);
    foreach($result['hits']['hits'] as $hit) {
      $assessments[]=$hit['_source'];
    }

    usort($assessments,function($a0,$a1){
      return $a0["metadata"]["modified"] > $a1["metadata"]["modified"];
    });

    $got=[];
    foreach($assessments as $p) {
      $name = $p['taxon']['scientificNameWithoutAuthorship'];
      if(isset($got[$name])) continue;
      else $got[$name]=true;
    }

    usort($assessments,function($a,$b) { 
      return strcmp($a['taxon']['family']." ".$a['taxon']['scientificNameWithoutAuthorship']
                   ,$b['taxon']['family']." ".$b['taxon']['scientificNameWithoutAuthorship']);
    });

    return $assessments;
  }

}


