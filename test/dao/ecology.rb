require 'rspec'
require 'yaml'
require_relative File.expand_path("src/lib/dao/ecology")

describe "EcologyDAO" do

    before(:all){
        @hash_fields = {
            :id => "",
            :family => "",
            :scientificNameWithoutAuthorship => "",
            :lifeForm => "", 
            :fenology  => "", 
            :luminosity => "",
            :substratum => "",
            :longevity => "", 
            :resprout => ""
        } 
    }   


    it "Should be a instance of a EcologyDAO." do
        ecology_dao = EcologyDAO.new
        expect(ecology_dao).to be_a EcologyDAO
        expect(ecology_dao.data.empty?).to be true
        expect(ecology_dao.profiles).to be_a Array
        expect(ecology_dao.profiles.empty?).to be false
    end

    it "Should generate data" do      
        ecology_dao = EcologyDAO.new
        expect(ecology_dao.data.empty?).to be true
        ecology_dao.generate_data
        expect(ecology_dao.data.empty?).to be false
        expect(ecology_dao.data.first.keys).to eq(@hash_fields.keys)
        expect(ecology_dao.data.first[:id]).to eq("urn:lsid:cncflora.jbrj.gov.br:profile:justicia:clivalis:1374083792")
        expect(ecology_dao.data.first.values).to include("ACANTHACEAE", "Justicia clivalis", "bush", "perenifolia", "","unkown")
        expect(ecology_dao.data.last.keys).to eq(@hash_fields.keys)
        expect(ecology_dao.data.last[:id]).to eq("urn:lsid:cncflora.jbrj.gov.br:profile:xyris:villosicarinata:1379511850")
        expect(ecology_dao.data.last.values).to include("XYRIDACEAE", "Xyris villosicarinata", "herb", "")
    end
end

