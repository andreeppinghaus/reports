require_relative File.expand_path('src/lib/dao/report')

class UseDAO < ReportDAO
    attr_accessor :data, :hash_fields

    def initialize(host,base)
        super
        @data = []
        @metadata_types = ["profile"] 
        @hash_fields = {
            :id => "",
            :family => "",
            :scientificNameWithoutAuthorship => "",
            :use => "",
            :resource => ""
        }
    end


    def generate_data

        set_docs_by_metadata_types

        if !( docs_by_metadata_types.empty? )

            family = ""
            scientificName = ""
            docs_by_metadata_types[ @metadata_types[0] ].each{ |profile|

                doc = profile["doc"]
                uses = doc["uses"] if doc["uses"]
                if uses && uses.is_a?(Array)

                    taxon = doc["taxon"] if doc["taxon"]
                    family = taxon["family"] if taxon["family"]
                    scientificName = taxon["scientificNameWithoutAuthorship"] if taxon["scientificNameWithoutAuthorship"]

                    uses.each{ |k|
                        @hash_fields[:id] = doc["_id"] 
                        @hash_fields[:family] = family
                        @hash_fields[:scientificNameWithoutAuthorship] = scientificName
                        @hash_fields[:use] = k["use"] 
                        @hash_fields[:resource] = k["resource"] 
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
                    h[:use],
                    h[:resource]
                ]
            }

        end

    end

end
