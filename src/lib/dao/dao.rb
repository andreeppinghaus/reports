require 'cncflora_commons'
require 'yaml'

class DAO
    attr_accessor :uri, :base
    attr_reader :docs, :metadata_types

    # The uri parameter of DAO must come from config settings. It's must be refactored.
    def initialize(base="_all_dbs",uri="http://localhost:5984")
        @uri = uri
        @base = base
        @docs = {}
        @metadata_types = {
            :assessment=>"assessment",
            :occurrence=>"occurrence",
            :profile=>"profile",
            :taxon=>"taxon"
        }
    end
    
    def get_all_databases
        all_dbs = http_get("#{@uri}/#{@base}")
    end


    # The method must be private?
    def generate_docs!(base)
        # Handling  exception - base
        if !base.empty?
            @base=base
        end
        uri = "#{@uri}/#{@base}/_all_docs?include_docs=true"
        @docs = http_get(uri)
    end


    def generate_list_of_hash_docs
        list_of_hash_docs = []
        list_of_hash_docs = @docs["rows"] if (!@docs.empty? && @docs["rows"] && !@docs["rows"].empty?)
        list_of_hash_docs
    end


    def get_docs_by_metadata_type(base,type)
        docs = []
        if @docs.empty?
            generate_docs!(base)
        end
        generate_list_of_hash_docs.each{|r|
            docs.push(r["doc"]) if r["doc"]["metadata"]["type"] == type
        }
        docs
    end

    def generate_data_lists_by_metadata_type(list_types)
        hash_data = {}
        list_types.each{ |type|
            hash_data[type] = get_docs_by_metadata_type @base,type
        }
        hash_data
    end

end
