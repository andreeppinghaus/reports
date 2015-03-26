require 'cncflora_commons'

class DAO
    attr_accessor :uri, :base
    attr_reader :docs

    def initialize(uri,base=nil)
        @uri = uri
        
        base ||= "_all_dbs"
        @base = base
        @docs = {}
    end
    

    def get_all_databases
        all_dbs = http_get("#{@uri}/#{@base}")
    end


    def get_docs!(base)
        # handling  exception - base
        if !base.empty?
            @base=base
        end
        uri = "#{@uri}/#{@base}/_all_docs?include_docs=true"
        @docs = http_get(uri)
    end


    def get_docs_by_type(base,type)        
        get_docs!(base)["rows"] if @docs.empty?
        docs = []
        _docs = @docs["rows"]
        _docs.each{ |r|
            docs.push(r["doc"]) if r["doc"]["metadata"]["type"] == type
        }
        docs
    end

end
