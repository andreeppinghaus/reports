require 'rspec'
require 'yaml'
require_relative File.expand_path("src/lib/dao/dispersion")

describe "DispersionDAO" do

    before(:all){
        @config = YAML.load_file(File.expand_path('config.yml'))["test"]
        @host = @config["couchdb"] 
        @base = @config["base_list"]
        @metadata_types = { "profile" => { "reproduction" => { "dispersionSyndrome" => 20  } } }
        @hash_fields = {
            :id => "",
            :family => "",
            :scientificNameWithoutAuthorship  => "",
            :dispersion => ""
        }
        @data = [
            {
                :id=>"urn:lsid:cncflora.jbrj.gov.br:profile:duguetia:rotundifolia:1375980120", 
                :family=>"ANNONACEAE", 
                :scientificNameWithoutAuthorship=>"Duguetia rotundifolia", 
                :dispersion=>"zoochory"
            },
            {
                :id=>"urn:lsid:cncflora.jbrj.gov.br:profile:vochysia:rotundifolia:1377262809", 
                :family=>"VOCHYSIACEAE", 
                :scientificNameWithoutAuthorship=>"Vochysia rotundifolia", 
                :dispersion=>"anemochory"
            }
        ]
    } 


    it "Should be an instance of the DispersionDAO class." do
        dao = DispersionDAO.new @host, @base
        expect( dao ).to be_an_instance_of DispersionDAO
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


    it "Should generate data of the dispersions report." do      
        dao = DispersionDAO.new @host, @base
        expect( dao.data ).to be_empty
        dao.generate_data        
        expect( dao.data.count ).to eq @metadata_types["profile"]["reproduction"]["dispersionSyndrome"]
        expect( dao.data.first ).to eq @data.first
        expect( dao.data.last ).to eq @data.last
    end

end
