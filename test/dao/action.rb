require 'rspec'
require 'yaml'
require_relative File.expand_path("src/lib/dao/action")

describe "ActionDAO" do

    before(:all){
        @config = YAML.load_file(File.expand_path('config.yml'))["test"]
        @host = @config["couchdb"] 
        @base = @config["base_list"]
        @metadata_types = { "profile" => { "actions" => 291 } }
        @hash_fields = {
            :id => "",
            :family => "",
            :scientificNameWithoutAuthorship  => "",
            :action => "",
            :situation => ""
        }
        @data = [
            {
                :id=>"urn:lsid:cncflora.jbrj.gov.br:profile:justicia:clivalis:1374083792",
                :family=>"ACANTHACEAE", :scientificNameWithoutAuthorship=>"Justicia clivalis",
                :action=>"1.1 Site/area protection", :situation=>"on going"
            },
            {
                :id=>"urn:lsid:cncflora.jbrj.gov.br:profile:xyris:villosicarinata:1379511850",
                :family=>"XYRIDACEAE", :scientificNameWithoutAuthorship=>"Xyris villosicarinata",
                :action=>"1.1 Site/area protection", :situation=>"needed"
            }
        ]
    } 


    it "Should be an instance of the ActionDAO class." do
        dao = ActionDAO.new @host, @base
        expect( dao ).to be_an_instance_of ActionDAO
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


    it "Should generate data of the actions report." do 
        dao = ActionDAO.new @host, @base
        expect( dao.data ).to be_empty 
        dao.generate_data        
        expect( dao.data.count ).to eq @metadata_types["profile"]["actions"]
        expect( dao.data.first ).to eq @data.first
        expect( dao.data.last ).to eq @data.last
    end

end
