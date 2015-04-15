require 'rspec'
require 'yaml'
require_relative File.expand_path("src/lib/dao/use")

describe "UseDAO" do

    before(:all){
        @config = YAML.load_file(File.expand_path('config.yml'))["test"]
        @host = @config["couchdb"] 
        @base = @config["base_list"]
        @metadata_types = { "profile" => { "uses" => 37 } }
        @hash_fields = {
            :id => "",
            :family => "",
            :scientificNameWithoutAuthorship  => "",
            :use => "",
            :resource => ""
        }
        @data = [
            {
                :id=>"urn:lsid:cncflora.jbrj.gov.br:profile:alstroemeria:chapadensis:1377109454", 
                :family=>"ALSTROEMERIACEAE", :scientificNameWithoutAuthorship=>"Alstroemeria chapadensis", 
                :use=>"17. Unknown", :resource=>nil
            },
            {
                :id=>"urn:lsid:cncflora.jbrj.gov.br:profile:xyris:villosicarinata:1379511850", 
                :family=>"XYRIDACEAE", :scientificNameWithoutAuthorship=>"Xyris villosicarinata", 
                :use=>"13. Pets/display animals, horticulture", :resource=>"flower"
            }
        ]
    } 


    it "Should be an instance of the UseDAO class." do
        dao = UseDAO.new @host, @base
        expect( dao ).to be_an_instance_of UseDAO
        expect( dao ).not_to be_an_instance_of ReportDAO
        expect( dao ).to be_kind_of ReportDAO
        expect( dao.host ).to eq @host
        expect( dao.base ).to eq @base
        expect( dao.docs_by_metadata_types ).to be_a Hash
        expect( dao.docs_by_metadata_types ).to be_empty
        expect( dao.metadata_types ).to be_a Array
        expect( dao.metadata_types ).to eq @metadata_types.keys
        expect( dao.hash_fields).to eq @hash_fields
        expect( dao.data ).to be_a Array
        expect( dao.data ).to be_empty 
    end


    it "Should generate data of the uses report." do 
        dao = UseDAO.new @host, @base
        expect( dao.data ).to be_empty 
        dao.generate_data        
        expect( dao.data.count ).to eq @metadata_types["profile"]["uses"]
        expect( dao.data.first ).to eq @data.first
        expect( dao.data.last ).to eq @data.last
    end

end
