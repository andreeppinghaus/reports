require_relative File.expand_path('src/lib/dao/report')

class ActionDAO < ReportDAO
    attr_accessor :data, :hash_fields

    def initialize(host,base)
        super
        @data = []
        @metadata_types = ["profile"] 
        @hash_fields = {
            :id => "",
            :family => "",
            :scientificNameWithoutAuthorship => "",
            :action => "",
            :situation => ""
        }
    end


    def generate_data

        set_docs_by_metadata_types

        if !( docs_by_metadata_types.empty? )

            family = ""
            scientificName = ""
            docs_by_metadata_types[ @metadata_types[0] ].each{ |profile|

                doc = profile["doc"]
                actions = doc["actions"] if doc["actions"]
                if actions && actions.is_a?(Array)

                    taxon = doc["taxon"] if doc["taxon"]
                    family = taxon["family"] if taxon["family"]
                    scientificName = taxon["scientificNameWithoutAuthorship"] if taxon["scientificNameWithoutAuthorship"]

                    actions.each{ |k|
                        @hash_fields[:id] = doc["_id"] 
                        @hash_fields[:family] = family
                        @hash_fields[:scientificNameWithoutAuthorship] = scientificName
                        @hash_fields[:action] = k["action"] 
                        @hash_fields[:situation] = k["situation"] 
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
                    h[:action],
                    h[:situation]
                ]
            }

        end

    end

end
