require 'cncflora_commons'
require 'yaml'

HOST = YAML.load_file(File.expand_path('config.yml'))['development']['couchdb']
BASE = YAML.load_file(File.expand_path('config.yml'))['development']['base_list']   

class DAO

    attr_accessor :host, :base

    def initialize(host=HOST,base=BASE) 
        @host = host
        @base = base
    end


    def all_dbs
        http_get("#{@host}/_all_dbs")
    end


    def get_rows_of_document
        get_all_docs["rows"]
    end


    def get_all_docs(base=@base,include_docs=true)        
        uri = "#{@host}/#{base}/_all_docs?include_docs=#{include_docs}"        
        http_get(uri)
    end

    private :get_all_docs

end
