require 'rspec'
require 'yaml'
require_relative File.expand_path("src/lib/dao/habitat")

describe "HabitatDAO" do

    before(:all){

        @hash_fields = {
            :id => "",
            :family => "",
            :scientificNameWithoutAuthorship  => "",
            :habitat => ""
        }   
    } 


    it "Should be a instance of a HabitatDAO." do
        habitat_dao = HabitatDAO.new
        expect(habitat_dao).to be_a HabitatDAO
        expect(habitat_dao.data.empty?).to be true
        expect(habitat_dao.profiles).to be_a Array
        expect(habitat_dao.profiles.empty?).to be false
        expect(habitat_dao.hash_fields).to eq(@hash_fields) 
    end


    it "Should generate data" do      
        habitat_dao = HabitatDAO.new
        expect(habitat_dao.data.empty?).to be true
        habitat_dao.generate_data
        expect(habitat_dao.data.empty?).to be false
        expect(habitat_dao.data.first.keys).to eq(@hash_fields.keys)
        expect(habitat_dao.data.first[:id]).to eq("urn:lsid:cncflora.jbrj.gov.br:profile:justicia:clivalis:1374083792")
        expect(habitat_dao.data.first.values).to include("ACANTHACEAE", "Justicia clivalis","2.2 Moist Savana")
        expect(habitat_dao.data.last.keys).to eq(@hash_fields.keys)
        expect(habitat_dao.data.last[:id]).to eq("urn:lsid:cncflora.jbrj.gov.br:profile:xyris:villosicarinata:1379511850")
        expect(habitat_dao.data.last.values).to include("XYRIDACEAE", "Xyris villosicarinata", "4.7 Subtropical/Tropical High Altitude Grassland")
    end

end
