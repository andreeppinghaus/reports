require_relative File.expand_path('src/lib/dao/dao')

class EcologyDAO
    attr_accessor :data, :hash_fields
    attr_reader :profiles

    def initialize()
        # The base parameter of DAO must come from config settings. It's must be refactored.
        dao = DAO.new YAML.load_file(File.expand_path('config.yml'))['development']['base_list']
        @data = []
        @profiles = dao.generate_data_lists_by_metadata_type(["profile"])["profile"]
        @hash_fields = {
            :id => "",
            :family => "",
            :scientificNameWithoutAuthorship => "",
            :lifeForm => "", 
            :fenology  => "", 
            :luminosity => "",
            :substratum => "",
            :longevity => "", 
            :resprout => ""
        }
    end


    def generate_data
        # Problem? fenology is a key of reprodution key. Check the profile:54342ab412c74 _id.
        @profiles.each{ |profile|
            if profile["ecology"] && profile["ecology"].is_a?(Hash)

                family = ""
                scientificName = ""
                if profile["taxon"] && profile["taxon"].is_a?(Hash) 
                    taxon = profile["taxon"]
                    family = taxon["family"] if taxon["family"] 
                    scientificName = taxon["scientificNameWithoutAuthorship"] if taxon["scientificNameWithoutAuthorship"]
                end

                lifeForm = ""
                fenology = ""
                luminosity = "" 
                substratum = "" 
                longevity = ""
                resprout = "" 

                ecology = profile["ecology"]
                lifeForm = ecology["lifeForm"] if ecology["lifeForm"]
                fenology = ecology["fenology"] if ecology["fenology"]
                luminosity = ecology["luminosity"] if ecology["luminosity"]
                substratum = ecology["substratum"] if ecology["substratum"]
                longevity = ecology["longevity"] if ecology["longevity"]
                resprout = ecology["resprout"] if ecology["resprout"]

                @hash_fields[:id] = profile["_id"]
                @hash_fields[:family] = family 
                @hash_fields[:scientificNameWithoutAuthorship] = scientificName 
                @hash_fields[:lifeForm] = lifeForm
                @hash_fields[:fenology] = fenology
                @hash_fields[:luminosity] = luminosity
                @hash_fields[:substratum] = substratum
                @hash_fields[:longevity] = longevity
                @hash_fields[:resprout] = resprout
                _hash_fields = @hash_fields.clone
                @data.push(_hash_fields) 
                clean_hash_fields
            end

        }

        @data.sort_by!{|h| 
            [ 
                h[:family],
                h[:scientificNameWithoutAuthorship],
                h[:lifeForm],
                h[:fenology],
                h[:luminosity],
                h[:substratum],
                h[:longevity],
                h[:resprout]
            ]
        }        
    end

    def clean_hash_fields
        @hash_fields.each{ |k,v|
            @hash_fields[k] = ""
        }        
    end

end
