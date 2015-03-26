require 'rspec'
require 'yaml'
require_relative File.expand_path("src/lib/dao/bioma")

describe "BiomaDAO" do

    before(:all){
        @config = YAML.load_file(File.expand_path('config.yml'))["test"]
        @uri = @config["couchdb"] 
        @dao = BiomaDAO.new
        @base_list = @config["base_list"]
        @all_biomas = ["Amazônia", "Caatinga", "Cerrado", "Mata Atlântica", "Pampa (Campos Sulinos)", "Pantanal"]
    }


    it "Should be a instance of a BiomaDAO." do
        bioma_dao = BiomaDAO.new
        expect(bioma_dao).to be_a BiomaDAO
        expect(bioma_dao.dao.uri).to eq(@config["couchdb"])        
        expect(bioma_dao.dao.base).to eq(@config["base_list"])        
        expect(bioma_dao.data).to be_a Array
        expect(bioma_dao.data.empty?).to be true
        expect(bioma_dao.biomas).to be_a Array
        expect(bioma_dao.biomas.empty?).to be true
    end

    it "Should check all biomas." do
        expect(@dao.biomas.empty?).to be true
        @dao.generate_all_biomas
        expect(@dao.biomas.count).to be @all_biomas.count
        @dao.biomas.each{ |bioma|
            expect(@all_biomas).to include bioma
        }
    end

    it "Should generate data" do
        expect(@dao.data.empty?).to be true
        @dao.generate_data
        @dao.data.each{ |data|
            expect(data).to be_a Hash
            expect(data.keys).to include :family and :scientificNameWithoutAuthorship and :bioma
            expect(@all_biomas).to include data[:bioma]
            
        }
    end

end

