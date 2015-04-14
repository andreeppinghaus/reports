require 'rspec'
require 'yaml'
require_relative File.expand_path("src/lib/dao/synonym")

describe "SynonymDAO" do

    before(:all){
        @config = YAML.load_file(File.expand_path('config.yml'))["test"]
        @host = @config["couchdb"] 
        @base = @config["base_list"]
        @metadata_types = { "taxon" => { "taxonomicStatus" => { "accepted" => 613, "synonym" => 21  }  } }
        @hash_fields = {
            :id => "",
            :family => "",
            :scientificNameWithoutAuthorship  => "",
            :scientificNameAuthorship => "",
            :acceptedNameUsage => ""
        }   
        @data =[
            {
                :id=>"urn:lsid:cncflora.jbrj.gov.br:taxon:species:angiosperm:ACANTHACEAE:Justicia clivalis", 
                :family=>"ACANTHACEAE", 
                :scientificNameWithoutAuthorship=>"Justicia clivalis", 
                :scientificNameAuthorship=>"Wassh.",
                :acceptedNameUsage=>"Justicia clivalis"
            },
            {
                :id=>"urn:lsid:cncflora.jbrj.gov.br:taxon:species:angiosperm:XYRIDACEAE:Xyris villosicarinata",
                :family=>"XYRIDACEAE", 
                :scientificNameWithoutAuthorship=>"Xyris villosicarinata", 
                :scientificNameAuthorship=>"Kral & Wand.",
                :acceptedNameUsage=>"Xyris villosicarinata"
            }
        ]
    } 


    it "Should be an instance of the SynonymDAO class." do
        dao = SynonymDAO.new @host, @base
        expect( dao ).to be_an_instance_of SynonymDAO
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


    it "Should generate data of the synonyms report." do      
        dao = SynonymDAO.new @host, @base
        expect( dao.data ).to be_empty
        dao.generate_data        
        expect( dao.data.count ).to eq @metadata_types["taxon"]["taxonomicStatus"]["accepted"]
        expect( dao.data.first ).to eq @data.first
        expect( dao.data.last ).to eq @data.last
    end

end
