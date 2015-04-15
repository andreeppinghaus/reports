require 'rspec'
require 'yaml'
require_relative File.expand_path("src/lib/dao/threat")

describe "ThreatDAO" do

    before(:all){
        @config = YAML.load_file(File.expand_path('config.yml'))["test"]
        @host = @config["couchdb"] 
        @base = @config["base_list"]
        @metadata_types = { "profile" => { "threats" => 582 } }
        @hash_fields = {
            :id => "",
            :family => "",
            :scientificNameWithoutAuthorship  => "",
            :threat => "",
            :incidence => "",
            :timing => "",
            :decline => ""
        }
        @data = [
            {
                :id=>"urn:lsid:cncflora.jbrj.gov.br:profile:justicia:clivalis:1374083792", 
                :family=>"ACANTHACEAE", :scientificNameWithoutAuthorship=>"Justicia clivalis", 
                :threat=>"3.2 Mining & quarrying", :incidence=>"regional", :timing=>"", :decline=>["habitat"], :timming=>nil
            },
            {
                :id=>"urn:lsid:cncflora.jbrj.gov.br:profile:xyris:villosicarinata:1379511850", 
                :family=>"XYRIDACEAE", :scientificNameWithoutAuthorship=>"Xyris villosicarinata", 
                :threat=>"3.2 Mining & quarrying", :incidence=>"local", :timing=>"", 
                :decline=>["locality", "habitat", "occurrence", "occupancy"], :timming=>nil
            }
        ]
    } 


    it "Should be an instance of the ThreatDAO class." do
        dao = ThreatDAO.new @host, @base
        expect( dao ).to be_an_instance_of ThreatDAO
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


    it "Should generate data of the threats report." do 
        dao = ThreatDAO.new @host, @base
        expect( dao.data ).to be_empty 
        dao.generate_data        
        expect( dao.data.count ).to eq @metadata_types["profile"]["threats"]
        expect( dao.data.first ).to eq @data.first
        expect( dao.data.last ).to eq @data.last
    end

end
