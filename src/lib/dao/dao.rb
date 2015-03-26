require 'cncflora_commons'
require 'yaml'

class DAO
    attr_accessor :uri, :base
    attr_reader :docs

    # The uri parameter of DAO must come from config settings. It's must be refactored.
    def initialize(base="_all_dbs",uri="http://localhost:5984")
        @uri = uri
        @base = base
        @docs = {}
    end
    
    # The method is needed?
    def get_all_databases
        all_dbs = http_get("#{@uri}/#{@base}")
    end


    # The method must be private?
    def get_docs!(base)
        # Handling  exception - base
        if !base.empty?
            @base=base
        end
        uri = "#{@uri}/#{@base}/_all_docs?include_docs=true"
        @docs = http_get(uri)
    end


    def get_docs_by_metadata_type(base,metadata_type)        
        get_docs!(base)["rows"] if @docs.empty?
        docs = []
        _docs = @docs["rows"]
        _docs.each{ |r|
            docs.push(r["doc"]) if r["doc"]["metadata"]["type"] == metadata_type
        }
        docs
    end

end
