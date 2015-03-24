require 'cncflora_commons'

class DAO
    attr_accessor :uri, :base

    def initialize(uri,base=nil)
        @uri = uri
        
        base ||= "_all_dbs"
        @base = base
    end
    

    def get_all_databases
        all_dbs = http_get("#{@uri}/#{@base}")
    end


    def get_docs!(base)
        if !base.empty?
            @base=base
        end
        uri = "#{@uri}/#{@base}/_all_docs?include_docs=true"
        docs = http_get(uri)
    end
end
