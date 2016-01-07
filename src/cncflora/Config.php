<?php
namespace cncflora;

use Symfony\Component\Yaml\Yaml;

class Config {

  public static $configured;
  public static $config;
  public static $_couchdb;

  public static function config() {
    if(self::$configured) return;
    self::$config=new Config;

    $raw = Yaml::parse(file_get_contents( __DIR__."/../../config/settings.yml" ));
    
    if(!defined('ENV')) {
      $env = getenv("PHP_ENV");
      if($env == null) {
        $env = 'development';
      }
      define('ENV',$env);
    }

    $data=$raw[$env];

    foreach($data as $key=>$value) {
      preg_match_all('/\$([a-zA-Z]+)/',$value,$reg);
      if(count($reg[0]) >= 1) {
        $e = getenv($reg[1][0]);
        $data[$key] = str_replace($reg[0][0],$e,$value);
      }
    }

    foreach($data as $k=>$v) {
      if(!defined($k)) {
        define(strtoupper($k),$v);
        self::$config->$k=$v;
      }
    }

    self::$configured=true;
  }

  public static function couchdb($db=''){
    $couchdb_parts = explode(":",self::$config->couchdb);
    $couchdb=array();
    $couchdb['host']=substr($couchdb_parts[1] ,2);
    $couchdb['port']=$couchdb_parts[2];

    $opts = array_merge($couchdb,['dbname'=>$db]);
    return \Doctrine\CouchDB\CouchDBClient::create($opts);
  }

  public static function elasticsearch() {
    $client = \Elasticsearch\ClientBuilder::create()
              ->setHosts([ELASTICSEARCH])
              ->build();
    return $client;
  }
}

