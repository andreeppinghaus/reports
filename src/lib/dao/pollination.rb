require_relative File.expand_path('src/lib/dao/dao')

class PollinationDAO
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
            :pollination => ""
        }
    end


    def generate_data
        @profiles.each{ |profile|
            reproduction = profile["reproduction"] if profile["reproduction"]
            if reproduction && reproduction["pollinationSyndrome"] && reproduction["pollinationSyndrome"].is_a?(Array)
                pollinationSyndrome = reproduction["pollinationSyndrome"]
                family = ""
                scientificName = ""
                if profile["taxon"] && profile["taxon"].is_a?(Hash) 
                    taxon = profile["taxon"]
                    family = taxon["family"] if taxon["family"] 
                    scientificName = taxon["scientificNameWithoutAuthorship"] if taxon["scientificNameWithoutAuthorship"]
                end
                pollinationSyndrome.each{ |pollination|
                    @hash_fields[:id] = profile["_id"] 
                    @hash_fields[:family] = family
                    @hash_fields[:scientificNameWithoutAuthorship] = scientificName
                    @hash_fields[:pollination] = pollination
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
                h[:pollination]
            ]
        }        
    end

    def clean_hash_fields
        @hash_fields.each{ |k,v|
            @hash_fields[k] = ""
        }        
    end

end