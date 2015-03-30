require 'rspec'
require 'yaml'
require_relative File.expand_path("src/lib/dao/pollination")

describe "PollinationDAO" do

    before(:all){

        @hash_fields = {
            :id => "",
            :family => "",
            :scientificNameWithoutAuthorship  => "",
            :pollination => ""
        }   
    } 


    it "Should be a instance of a PollinationDAO." do
        dispersion_dao = PollinationDAO.new
        expect(dispersion_dao).to be_a PollinationDAO
        expect(dispersion_dao.data.empty?).to be true
        expect(dispersion_dao.profiles).to be_a Array
        expect(dispersion_dao.profiles.empty?).to be false
        expect(dispersion_dao.hash_fields).to eq(@hash_fields) 
    end


    it "Should generate data" do      
        dispersion_dao = PollinationDAO.new
        expect(dispersion_dao.data.empty?).to be true
        dispersion_dao.generate_data
        expect(dispersion_dao.data.empty?).to be false
        expect(dispersion_dao.data.first.keys).to eq(@hash_fields.keys)
        expect(dispersion_dao.data.first[:id]).to eq("urn:lsid:cncflora.jbrj.gov.br:profile:justicia:clivalis:1374083792")
        expect(dispersion_dao.data.first.values).to include("ACANTHACEAE", "Justicia clivalis","ornitophily")
        expect(dispersion_dao.data.last.keys).to eq(@hash_fields.keys)
        expect(dispersion_dao.data.last[:id]).to eq("urn:lsid:cncflora.jbrj.gov.br:profile:vochysia:pygmaea:1381429089")
        expect(dispersion_dao.data.last.values).to include("VOCHYSIACEAE", "Vochysia pygmaea", "melitophily")
    end

end
