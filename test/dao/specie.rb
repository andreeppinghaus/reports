require 'rspec'
require 'yaml'
require_relative File.expand_path("src/lib/dao/specie")

describe "SpecieDAO" do

    before(:all){

        @hash_fields = {
            :id => "",
            :family => "",
            :scientificNameWithoutAuthorship  => "",
            :scientificNameAuthorship => ""
        }   
    } 


    it "Should be a instance of a SpecieDAO." do
        specie_dao = SpecieDAO.new
        expect(specie_dao).to be_a SpecieDAO
        expect(specie_dao.data.empty?).to be true
        expect(specie_dao.taxons).to be_a Array
        expect(specie_dao.taxons.empty?).to be false
        expect(specie_dao.hash_fields).to eq(@hash_fields) 
    end


    it "Should generate data" do      
        specie_dao = SpecieDAO.new
        expect(specie_dao.data.empty?).to be true
        specie_dao.generate_data
        expect(specie_dao.data.empty?).to be false
        expect(specie_dao.data.first.keys).to eq(@hash_fields.keys)
        evaluated_id = "urn:lsid:cncflora.jbrj.gov.br:taxon:species:angiosperm:ACANTHACEAE:Justicia clivalis"
        expect(specie_dao.data.first[:id]).to eq(evaluated_id)
        expect(specie_dao.data.first.values).to include("ACANTHACEAE", "Justicia clivalis", "Wassh.")
        expect(specie_dao.data.last.keys).to eq(@hash_fields.keys)
        evaluated_id = "urn:lsid:cncflora.jbrj.gov.br:taxon:species:angiosperm:XYRIDACEAE:Xyris villosicarinata"
        expect(specie_dao.data.last[:id]).to eq(evaluated_id)
        expect(specie_dao.data.last.values).to include("XYRIDACEAE", "Xyris villosicarinata", "Kral & Wand.")
    end

end
