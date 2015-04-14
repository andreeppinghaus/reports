require_relative File.expand_path('src/lib/dao/report')

class SpecieDAO < ReportDAO
    attr_accessor :data, :hash_fields

    def initialize(host,base)
        super
        @data = []
        @metadata_types = ["taxon"]
        @hash_fields = {
            :id => "",
            :family => "",
            :scientificNameWithoutAuthorship => "",
            :scientificNameAuthorship=> ""
        }
    end


    def generate_data

        set_docs_by_metadata_types
        @docs_by_metadata_types[@metadata_types[0]].each{ |taxon|

            doc = taxon["doc"]
            taxonomicStatus = doc["taxonomicStatus"] if doc["taxonomicStatus"]
            if taxonomicStatus && taxonomicStatus == "accepted"
                family = ""
                scientificNameAuthorship = ""
                scientificNameWithoutAuthorship = ""

                family = doc["family"] if doc["family"] 
                scientificNameWithoutAuthorship = doc["scientificNameWithoutAuthorship"] if doc["scientificNameWithoutAuthorship"]
                scientificNameAuthorship = doc["scientificNameAuthorship"] if doc["scientificNameAuthorship"]

                @hash_fields[:id] = doc["_id"] 
                @hash_fields[:family] = family.upcase
                @hash_fields[:scientificNameWithoutAuthorship] = scientificNameWithoutAuthorship
                @hash_fields[:scientificNameAuthorship] = scientificNameAuthorship


                _hash_fields = @hash_fields.clone
                @data.push(_hash_fields) 
                clean_hash_fields
            end

        }

        @data.sort!{ |x,y| 
            array0 = [ x[:family], x[:scientificNameWithoutAuthorship], x[:scientificNameAuthorship] ] 
            array1 = [ y[:family], y[:scientificNameWithoutAuthorship], y[:scientificNameAuthorship] ] 
            array0 <=> array1
        }

    end

    def clean_hash_fields
        @hash_fields.each{ |k,v|
            @hash_fields[k] = ""
        }        
    end

end
