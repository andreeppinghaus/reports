require_relative File.expand_path('src/lib/dao/dao')

class EcologyDAO
    attr_accessor :data
    attr_reader :dao

    def initialize()
        # The base parameter of DAO must come from config settings. It's must be refactored.
        @dao = DAO.new YAML.load_file(File.expand_path('config.yml'))['development']['base_list']
        @data = []
    end


    def generate_data
        docs = @dao.get_docs_by_metadata_type(@dao.base,'profile')
        count = 0
        docs.each{ |doc|
            if doc["ecology"] && doc["ecology"].is_a?(Hash)
                _hash = {
                    :family => doc["taxon"]["family"],
                    :scientificNameWithoutAuthorship => doc["taxon"]["scientificNameWithoutAuthorship"],
                    :lifeForm => doc["ecology"]["lifeForm"],
                    :fenology  => doc["ecology"]["fenology"],
                    :luminosity => doc["ecology"]["luminosity"],
                    :substratum => doc["ecology"]["substratum"],
                    :longevity => doc["ecology"]["longevity"],
                    :resprout => doc["ecology"]["resprout"]
                }
                @data.push _hash
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

end
