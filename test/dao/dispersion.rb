require 'rspec'
require 'yaml'
require_relative File.expand_path("src/lib/dao/dispersion")

describe "DispersionDAO" do

    before(:all){

        @hash_fields = {
            :id => "",
            :family => "",
            :scientificNameWithoutAuthorship  => "",
            :dispersion => ""
        }   
    } 


    it "Should be a instance of a DispersionDAO." do
        dispersion_dao = DispersionDAO.new
        expect(dispersion_dao).to be_a DispersionDAO
        expect(dispersion_dao.data.empty?).to be true
        expect(dispersion_dao.profiles).to be_a Array
        expect(dispersion_dao.profiles.empty?).to be false
        expect(dispersion_dao.hash_fields).to eq(@hash_fields) 
    end


    it "Should generate data" do      
        dispersion_dao = DispersionDAO.new
        expect(dispersion_dao.data.empty?).to be true
        dispersion_dao.generate_data
        expect(dispersion_dao.data.empty?).to be false
        expect(dispersion_dao.data.first.keys).to eq(@hash_fields.keys)
        expect(dispersion_dao.data.first[:id]).to eq("urn:lsid:cncflora.jbrj.gov.br:profile:duguetia:rotundifolia:1375980120")
        expect(dispersion_dao.data.first.values).to include("ANNONACEAE", "Duguetia rotundifolia","zoochory")
        expect(dispersion_dao.data.last.keys).to eq(@hash_fields.keys)
        expect(dispersion_dao.data.last[:id]).to eq("urn:lsid:cncflora.jbrj.gov.br:profile:vochysia:rotundifolia:1377262809")
        expect(dispersion_dao.data.last.values).to include("VOCHYSIACEAE", "Vochysia rotundifolia", "anemochory")
    end

end
