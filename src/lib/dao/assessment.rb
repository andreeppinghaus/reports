require_relative File.expand_path('src/lib/dao/report')

class AssessmentDAO < ReportDAO
    attr_accessor :data, :hash_fields, :scientificNames

    def initialize(host,base)
        super
        @data = []
        @metadata_types = ["taxon","profile","assessment"]        
        @hash_fields = {
            :id_taxon => "",
            :id_profile => "",
            :id_assessment => "",
            :family => "",
            :scientificNameWithoutAuthorship => "",
            :scientificNameAuthorship => "",
            :analysis => "",
            :assessment => "",
            :category => "",
            :criteria => "",
            :rationale => ""
            
        }
    end


    def generate_data
        hash_by_specie = {}

        set_docs_by_metadata_types
        keys = docs_by_metadata_types.keys
        taxons = docs_by_metadata_types["taxon"] if docs_by_metadata_types["taxon"]
        profiles = docs_by_metadata_types["profile"] if docs_by_metadata_types["profile"]
        assessments = docs_by_metadata_types["assessment"] if docs_by_metadata_types["assessment"]
        if taxons

            taxons.each{ |taxon|

                doc = taxon["doc"]
                scientificName = doc["scientificNameWithoutAuthorship"]
                # It is a itereate from taxons. So it is not necessary do like tests: "if doc['taxonomicStatus'], for example."
                taxonomicStatus = doc["taxonomicStatus"]
                if taxonomicStatus && taxonomicStatus == "accepted"

                    _hash_fields = @hash_fields.clone
                    hash_by_specie[scientificName] = _hash_fields
                    _hash_fields[:id_taxon] = doc["_id"]
                    _hash_fields[:family] = doc["family"].upcase
                    _hash_fields[:scientificNameWithoutAuthorship] = scientificName 
                    _hash_fields[:scientificNameAuthorship] = doc["scientificNameAuthorship"]

                end

            }

        end


        if profiles 

            profiles.each{ |profile|
                
                doc = profile["doc"]
                
                # It is a profile from a taxon. So it is not necessary do like tests: "if doc['taxon'], for example."
                # All profile documents have its doc["metadata"]["status"], without exception. So, no test if it exists.
                status = doc["metadata"]["status"]
                scientificName = doc["taxon"]["scientificNameWithoutAuthorship"]
                if hash_by_specie[scientificName]
                    set_hash_by_specie( hash_by_specie, scientificName, "id_profile", doc["_id"] )
                    set_hash_by_specie( hash_by_specie, scientificName, "analysis", status )
                end
                
            }

        end


        if assessments 

            assessments.each{ |assessment|
                
                doc = assessment["doc"]
                status = doc["metadata"]["status"]
                scientificName = doc["taxon"]["scientificNameWithoutAuthorship"]
                if hash_by_specie[scientificName]
                    set_hash_by_specie( hash_by_specie, scientificName, "id_assessment", doc["_id"] )
                    set_hash_by_specie( hash_by_specie, scientificName, "assessment", status )
                    set_hash_by_specie( hash_by_specie, scientificName, "category", doc["category"] )
                    set_hash_by_specie( hash_by_specie, scientificName, "criteria", doc["criteria"] )
                end
            }


        end


        hash_by_specie.each{ |k,v|
            @data.push(v)
        }


        @data.sort!{ |x,y| 
            array0 = [ 
                x[:family], x[:scientificNameWithoutAuthorship] , x[:scientificNameAuthorship],
                x[:analysis], x[:assessment], x[:category], x[:criteria], x[:rationale]
            ] 
            array1 = [ 
                y[:family], y[:scientificNameWithoutAuthorship], y[:scientificNameAuthorship],
                y[:analysis], y[:assessment], y[:category], y[:criteria], y[:rationale]
            ] 
            array0 <=> array1
        }

    end

    def set_hash_by_specie(hash_by_specie={},scientificName,key,value)
        value = "" if value.nil?
            hash_by_specie[scientificName][key.to_sym] = value
    end

    private :set_hash_by_specie

end
