require_relative File.expand_path('src/lib/dao/report')

class PhytophysiognomieDAO < ReportDAO
    attr_accessor :data, :hash_fields

    def initialize(rows_of_document=nil)
        super(rows_of_document)
        @data = []
        @metadata_types = ["profile"] 
        @hash_fields = {
            :id => "",
            :family => "",
            :scientificNameWithoutAuthorship => "",
            :phytophysiognomie => ""
        }
    end


    def generate_data(types=@metadata_types)

        set_docs_by_metadata_types
        @docs_by_metadata_types[@metadata_types[0]].each{ |profile|

            doc = profile["doc"]
            ecology = doc["ecology"] if doc["ecology"]
            if ecology && ecology["fitofisionomies"] && ecology["fitofisionomies"].is_a?(Array)

                family = ""
                scientificName = ""
                phytophysiognomies = ecology["fitofisionomies"]


                taxon = doc["taxon"] if doc["taxon"]
                family = taxon["family"] if taxon["family"]
                scientificName = taxon["scientificNameWithoutAuthorship"] if taxon["scientificNameWithoutAuthorship"]

                phytophysiognomies.each{ |phytophysiognomie|
                    @hash_fields[:id] = doc["_id"] 
                    @hash_fields[:family] = family
                    @hash_fields[:scientificNameWithoutAuthorship] = scientificName
                    @hash_fields[:phytophysiognomie] = phytophysiognomie
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
                h[:phytophysiognomie]
            ]
        }        
    end

    def clean_hash_fields
        @hash_fields.each{ |k,v|
            @hash_fields[k] = ""
        }        
    end

end
