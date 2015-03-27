require_relative File.expand_path('src/lib/dao/dao')

class BiomaDAO

    attr_accessor :data, :biomas
    attr_reader :dao

    def initialize()
        # The base parameter of DAO must come from config settings. It's must be refactored.
        @dao = DAO.new YAML.load_file(File.expand_path('config.yml'))['development']['base_list']
        @data = []
        @biomas = []
    end


    def generate_data
        docs = @dao.get_docs_by_metadata_type(@dao.base,'profile')
        docs.each{ |doc|
            if doc["ecology"] && doc["ecology"]["biomas"] && doc["ecology"]["biomas"].is_a?(Array) 
                doc["ecology"]["biomas"].each{ |b|
                    family = doc["taxon"]["family"]
                    scientificNameWithoutAuthorship= doc["taxon"]["scientificNameWithoutAuthorship"]
                    @data.push({:family=>family,:scientificNameWithoutAuthorship=>scientificNameWithoutAuthorship,:bioma=>b}) 
                }
            end
        }
        @data.sort_by!{|h| [h[:family],h[:scientificNameWithoutAuthorship]]}
    end

    def generate_all_biomas
        if @biomas.empty?
            docs = @dao.get_docs_by_metadata_type(@dao.base,'profile')
            docs.each{ |doc|
                if doc["ecology"] && doc["ecology"]["biomas"] && doc["ecology"]["biomas"].is_a?(Array) 
                    doc["ecology"]["biomas"].each{ |b|
                        @biomas.push b
                    }
                end                
            }
            @biomas.uniq!.sort!
        end
    end

    
end
