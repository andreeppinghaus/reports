require_relative File.expand_path('src/lib/dao/report')

class HabitatDAO < ReportDAO
    attr_accessor :data, :hash_fields

    def initialize(host,base)
        super
        @data = []
        @metadata_types = ["profile"]
        @hash_fields = {
            :id => "",
            :family => "",
            :scientificNameWithoutAuthorship => "",
            :habitat => ""
        }
    end


    def generate_data

        set_docs_by_metadata_types
        
        if !( docs_by_metadata_types.empty? )

            family = ""
            scientificName = ""
            docs_by_metadata_types[ @metadata_types[0] ].each{ |profile|

                doc = profile["doc"]
                ecology = doc["ecology"] if doc["ecology"]
                habitats = ecology["habitats"] if ecology && ecology["habitats"]
                if habitats && habitats.is_a?(Array) 

                    taxon = doc["taxon"] if doc["taxon"]
                    family = taxon["family"] if taxon["family"]
                    scientificName = taxon["scientificNameWithoutAuthorship"] if taxon["scientificNameWithoutAuthorship"]

                    habitats.each{ |habitat|
                        @hash_fields[:id] = doc["_id"] 
                        @hash_fields[:family] = family
                        @hash_fields[:scientificNameWithoutAuthorship] = scientificName
                        @hash_fields[:habitat] = habitat
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
                    h[:habitat]
                ]
            }

        end

    end

end
