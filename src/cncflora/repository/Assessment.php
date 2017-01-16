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
      foreach($spps as $spp) {
        $names[] = $spp['scientificNameWithoutAuthorship'];
      }
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

    $got=[];
    $assessments=[];
    $result = $this->elasticsearch->search($params);
    foreach($result['hits']['hits'] as $hit) {
      $p = $hit['_source'];
      $name = $p['taxon']['scientificNameWithoutAuthorship'];
      if(isset($got[$name])) continue;
      $got[$name]=true;
      $assessments[]=$p;
    }

    usort($assessments,function($a0,$a1){
      return $a0["metadata"]["modified"] > $a1["metadata"]["modified"];
    });

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

    $got=[];
    $assessments=[];
    $result = $this->elasticsearch->search($params);
    foreach($result['hits']['hits'] as $hit) {
      $p = $hit['_source'];
      $name = $p['taxon']['scientificNameWithoutAuthorship'];
      if(isset($got[$name])) continue;
      $got[$name]=true;

      $assessments[]=$hit['_source'];
    }

    usort($assessments,function($a0,$a1){
      return $a0["metadata"]["modified"] > $a1["metadata"]["modified"];
    });

    usort($assessments,function($a,$b) {
      return strcmp($a['taxon']['family']." ".$a['taxon']['scientificNameWithoutAuthorship']
                   ,$b['taxon']['family']." ".$b['taxon']['scientificNameWithoutAuthorship']);
    });

    return $assessments;
  }

  public function listByFamily($family) {
    $names=[];

    $params=[
      'index'=>$this->db,
      'type'=>'assessment',
      'body'=>[
        'size'=> 9999,
        'query'=>[
          'match'=>[
          'family'=>[
              'query'=>$family
              ,'operator'=>'and'
            ]
          ]
        ]
      ]
    ];
    $result = $this->elasticsearch->search($params);
    foreach($result['hits']['hits'] as $k=>$hit) {
      if(isset($hit['_source']['assessor']))
        $names[$k]['assessor'] = trim($hit['_source']['assessor']);
      if(isset($hit['_source']['evaluator']))
        $names[$k]['evaluator'] = trim($hit['_source']['evaluator']);
    }

    return $names;
  }

  public function listCategoryByName($specieName) {
    $names=[];

    $params=[
      'index'=>$this->db,
      'type'=>'assessment',
      'body'=>[
        'size'=> 9999,
        'query'=>[
          'bool'=>[
            'should'=>[
                'match'=>[
                  'scientificNameWithoutAuthorship'=>['query'=>$specieName,'operator'=>'and']
                ]
             ]
          ]
        ]
      ]
    ];
    $result = $this->elasticsearch->search($params);
    foreach($result['hits']['hits'] as $k=>$hit) {
      if(isset($hit['_source']['category']))
        $names[$k] = trim($hit['_source']['category']);
    }
    return $names;
  }

}
