require_relative File.expand_path('src/lib/dao/dao')

class ReportDAO < DAO

    attr_accessor :metadata_types, :hash_fields
    attr_reader :docs_by_metadata_types, :rows_of_document


    def initialize(rows_of_document=nil)
        super()
        @rows_of_document = rows_of_document
        @docs_by_metadata_types = {}
        @metadata_types = ["assessment","occurrence","profile","taxon"]
        @hash_fields = {}
    end


    def all_dbs
        all_dbs = super
        all_dbs.select{ |db| !db.end_with? "_history"} - ["_replicator","_users"]
    end


    def set_docs_by_metadata_types(types=@metadata_types)

        @rows_of_document.each{ |r|
            doc = r["doc"]
            type = doc["metadata"]["type"]
            if types.include? type
                @docs_by_metadata_types[type] = [] if !@docs_by_metadata_types[type]
                @docs_by_metadata_types[type].push r
            end
        }
    end



end
