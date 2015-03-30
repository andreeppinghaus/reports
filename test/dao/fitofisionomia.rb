require 'rspec'
require 'yaml'
require_relative File.expand_path("src/lib/dao/fitofisionomia")

describe "FitofisionomiaDAO" do

    before(:all){

        @hash_fields = {
            :id => "",
            :family => "",
            :scientificNameWithoutAuthorship  => "",
            :fitofisionomier => ""
        }   
    } 


    it "Should be a instance of a FitofisionomiaDAO." do
        fito_dao = FitofisionomiaDAO.new
        expect(fito_dao).to be_a FitofisionomiaDAO
        expect(fito_dao.data.empty?).to be true
        expect(fito_dao.profiles).to be_a Array
        expect(fito_dao.profiles.empty?).to be false
        expect(fito_dao.hash_fields).to eq(@hash_fields) 
    end


    it "Should generate data" do      
        fito_dao = FitofisionomiaDAO.new
        expect(fito_dao.data.empty?).to be true
        fito_dao.generate_data
        expect(fito_dao.data.empty?).to be false
        expect(fito_dao.data.first.keys).to eq(@hash_fields.keys)
        expect(fito_dao.data.first[:id]).to eq("urn:lsid:cncflora.jbrj.gov.br:profile:justicia:clivalis:1374083792")
        expect(fito_dao.data.first.values).to include("ACANTHACEAE", "Justicia clivalis","Floresta Estacional Sempre-verde de Submontana")
        expect(fito_dao.data.last.keys).to eq(@hash_fields.keys)
        expect(fito_dao.data.last[:id]).to eq("urn:lsid:cncflora.jbrj.gov.br:profile:xyris:villosicarinata:1379511850")
        expect(fito_dao.data.last.values).to include("XYRIDACEAE", "Xyris villosicarinata", "Refúgios Ecológicos Alto-montanos")
    end

end
