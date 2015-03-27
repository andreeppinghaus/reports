require 'rspec'
require 'yaml'
require_relative File.expand_path("src/lib/dao/ecology")

describe "EcologyDAO" do

    before(:all){
        @config = YAML.load_file(File.expand_path('config.yml'))["test"]
        @uri = @config["couchdb"] 
        @dao = EcologyDAO.new
        @base_list = @config["base_list"]
        @keys_of_data = [
            :family,
            :scientificNameWithoutAuthorship,
            :lifeForm,
            :fenology,
            :luminosity,
            :substratum,
            :longevity, 
            :resprout
        ]
    }   


    it "Should be a instance of a BiomaDAO." do
        action_dao = EcologyDAO.new
        expect(action_dao).to be_a EcologyDAO
        expect(action_dao.dao.uri).to eq(@config["couchdb"])            
        expect(action_dao.dao.base).to eq(@config["base_list"])            
        expect(action_dao.data).to be_a Array
        expect(action_dao.data.empty?).to be true
    end

    it "Should generate data" do      
        expect(@dao.data.empty?).to be true
        @dao.generate_data
        expect(@dao.data.empty?).to be false
        expect(@dao.data.first.keys).to eq(@keys_of_data)
        expect(@dao.data.first.values).to include("ACANTHACEAE", "Justicia clivalis", "bush", "perenifolia", nil, nil,"unkown")
        expect(@dao.data.last.keys).to eq(@keys_of_data) 
        expect(@dao.data.last.values).to include("XYRIDACEAE", "Xyris villosicarinata", "herb", nil)
    end
end

