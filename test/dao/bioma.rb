require 'rspec'
require 'yaml'
require_relative File.expand_path("src/lib/dao/bioma")

describe "BiomaDAO" do

    before(:all){
        @config = YAML.load_file(File.expand_path('config.yml'))["test"]
        @host = @config["couchdb"] 
        @base = @config["base_list"]
        @metadata_types = { "profile" => { "ecology" => { "biomas" => 600  } } }
        @hash_fields = {
            :id => "",
            :family => "",
            :scientificNameWithoutAuthorship  => "",
            :bioma => ""
        }
        @data =[
            {
                :id=>"urn:lsid:cncflora.jbrj.gov.br:profile:justicia:clivalis:1374083792", 
                :family=>"ACANTHACEAE", 
                :scientificNameWithoutAuthorship=>"Justicia clivalis", 
                :bioma=>"Cerrado"
            },
            {
                :id=>"urn:lsid:cncflora.jbrj.gov.br:profile:xyris:villosicarinata:1379511850",
                :family=>"XYRIDACEAE", 
                :scientificNameWithoutAuthorship=>"Xyris villosicarinata",
                :bioma=>"Cerrado"
            }
        ]
        @all_biomas = ["Amazônia", "Caatinga", "Cerrado", "Mata Atlântica", "Pampa (Campos Sulinos)", "Pantanal"]
    }


    it "Should be an instance of the BiomaDAO class." do
        dao = BiomaDAO.new @host, @base
        expect( dao ).to be_an_instance_of BiomaDAO
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


    it "Should generate data of the biomas report." do      
        dao = BiomaDAO.new @host, @base
        expect( dao.data ).to be_empty
        dao.generate_data        
        expect( dao.data.count ).to eq @metadata_types["profile"]["ecology"]["biomas"]
        expect( dao.data.first ).to eq @data.first
        expect( dao.data.last ).to eq @data.last
    end

end

