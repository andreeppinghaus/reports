require_relative File.expand_path('src/lib/dao/report')

class DispersionDAO < ReportDAO
    attr_accessor :data, :hash_fields

    def initialize(rows_of_document=nil)
        super(rows_of_document)
        @data = []
        @metadata_types = ["profile"]
        @hash_fields = {
            :id => "",
            :family => "",
            :scientificNameWithoutAuthorship => "",
            :dispersion => ""
        }
    end


    def generate_data(types=@metadata_types)

        set_docs_by_metadata_types
        @docs_by_metadata_types[@metadata_types[0]].each{ |profile|

            doc = profile["doc"]
            reproduction = doc["reproduction"] if doc["reproduction"]
            if reproduction && reproduction["dispersionSyndrome"] && reproduction["dispersionSyndrome"].is_a?(Array)
              
                dispersionSyndrome = reproduction["dispersionSyndrome"]
                family = ""
                scientificName = ""

                taxon = doc["taxon"]
                family = taxon["family"] if taxon["family"] 
                scientificName = taxon["scientificNameWithoutAuthorship"] if taxon["scientificNameWithoutAuthorship"]

                dispersionSyndrome.each{ |dispersion|
                    @hash_fields[:id] = doc["_id"] 
                    @hash_fields[:family] = family
                    @hash_fields[:scientificNameWithoutAuthorship] = scientificName
                    @hash_fields[:dispersion] = dispersion 
                }


                _hash_fields = @hash_fields.clone
                @data.push(_hash_fields) 
                clean_hash_fields
            end

        }

        @data.sort_by!{|h| 
            [ 
                h[:family],
                h[:scientificNameWithoutAuthorship],
                h[:dispersion]
            ]
        }        
    end

    def clean_hash_fields
        @hash_fields.each{ |k,v|
            @hash_fields[k] = ""
        }        
    end

end
