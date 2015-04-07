require_relative File.expand_path('src/lib/dao/report')

class BiomaDAO < ReportDAO
    attr_accessor :data, :hash_fields

    def initialize(rows_of_document=nil)
        super(rows_of_document)
        @data = []
        @metadata_types = ["profile"]
        @hash_fields = {
            :id => "",
            :family => "",
            :scientificNameWithoutAuthorship => "",
            :bioma=> ""
        }
    end


    def generate_data(types=@metadata_types)

        set_docs_by_metadata_types
        @docs_by_metadata_types[@metadata_types[0]].each{ |profile|

            doc = profile["doc"]
            if doc["ecology"] && doc["ecology"]["biomas"] && doc["ecology"]["biomas"].is_a?(Array) 

                biomas = doc["ecology"]["biomas"]
                family = ""
                scientificName = ""

                taxon = doc["taxon"]

                family = taxon["family"] if taxon["family"]
                scientificName = taxon["scientificNameWithoutAuthorship"] if taxon["scientificNameWithoutAuthorship"]


                biomas.each{ |bioma|
                    @hash_fields[:id] = doc["_id"] 
                    @hash_fields[:family] = family.upcase
                    @hash_fields[:scientificNameWithoutAuthorship] = scientificName
                    @hash_fields[:bioma] = bioma
                }

                _hash_fields = @hash_fields.clone
                @data.push(_hash_fields) 
                clean_hash_fields

            end
        }

        @data.sort!{ |x,y| 
            array0 = [ x[:family], x[:scientificNameWithoutAuthorship], x[:bioma] ] 
            array1 = [ y[:family], y[:scientificNameWithoutAuthorship], y[:bioma] ] 
            array0 <=> array1
        }

    end

    def clean_hash_fields
        @hash_fields.each{ |k,v|
            @hash_fields[k] = ""
        }        
    end
    
end
