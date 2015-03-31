require_relative File.expand_path('src/lib/dao/dao')

class SpecieDAO
    attr_accessor :data, :hash_fields
    attr_reader :taxons

    def initialize()
        # The base parameter of DAO must come from config settings. It's must be refactored.
        dao = DAO.new YAML.load_file(File.expand_path('config.yml'))['development']['base_list']
        @data = []
        @taxons = dao.generate_data_lists_by_metadata_type(["taxon"])["taxon"]
        @hash_fields = {
            :id => "",
            :family => "",
            :scientificNameWithoutAuthorship => "",
            :scientificNameAuthorship=> ""
        }
    end


    def generate_data
        @taxons.each{ |taxon|
            taxonomicStatus = taxon["taxonomicStatus"] if taxon["taxonomicStatus"]

            if taxonomicStatus && taxonomicStatus == "accepted"
                family = ""
                scientificNameAuthorship = ""
                scientificNameWithoutAuthorship = ""

                family = taxon["family"] if taxon["family"] 
                scientificNameWithoutAuthorship = taxon["scientificNameWithoutAuthorship"] if taxon["scientificNameWithoutAuthorship"]
                scientificNameAuthorship = taxon["scientificNameAuthorship"] if taxon["scientificNameAuthorship"]

                @hash_fields[:id] = taxon["_id"] 
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
