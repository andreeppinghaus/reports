require 'rspec'
require 'yaml'
require_relative File.expand_path("src/lib/dao/assessment")

describe "AssessmentDAO" do

    before(:all){
        @config = YAML.load_file(File.expand_path('config.yml'))["test"]
        @host = @config["couchdb"] 
        @base = @config["base_list"]
        @metadata_types = { 
            "taxon" => 634, 
            "profile" => 615, 
            "assessment" => 605 
        }
        @data_count = 613
        @hash_fields = {
            :id_taxon => "",
            :id_profile => "",
            :id_assessment => "",
            :family => "",
            :scientificNameWithoutAuthorship => "",
            :scientificNameAuthorship => "",
            :analysis => "",
            :assessment => "",
            :category => "",
            :criteria => "",
            :rationale => ""
          }
        @data = [
            {
                :id_taxon=>"urn:lsid:cncflora.jbrj.gov.br:taxon:species:angiosperm:ACANTHACEAE:Justicia clivalis", 
                :id_profile=>"urn:lsid:cncflora.jbrj.gov.br:profile:justicia:clivalis:1374083792", 
                :id_assessment=>"c0d446cd482d523bf99317b92ec25c57", 
                :family=>"ACANTHACEAE", :scientificNameWithoutAuthorship=>"Justicia clivalis", :scientificNameAuthorship=>"Wassh.", 
                :analysis=>"done", :assessment=>"comments", :category=>"NT", :criteria=>"", :rationale=>""
            },
            {
                :id_taxon=>"urn:lsid:cncflora.jbrj.gov.br:taxon:species:angiosperm:XYRIDACEAE:Xyris villosicarinata", 
                :id_profile=>"urn:lsid:cncflora.jbrj.gov.br:profile:xyris:villosicarinata:1379511850", 
                :id_assessment=>"83cb2af5388bb46e2c7ac951673e148c", 
                :family=>"XYRIDACEAE", :scientificNameWithoutAuthorship=>"Xyris villosicarinata", 
                :scientificNameAuthorship=>"Kral & Wand.", 
                :analysis=>"done", :assessment=>"comments", :category=>"DD", :criteria=>"", :rationale=>""
            }
        ]
    } 


    it "Should be an instance of the AssessmentDAO class." do
        dao = AssessmentDAO.new @host, @base
        expect( dao ).to be_an_instance_of AssessmentDAO
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


    it "Should generate data of the assessments report." do 
        dao = AssessmentDAO.new @host, @base
        expect( dao.data ).to be_empty 
        dao.generate_data        
        expect( dao.data.count ).to eq @data_count
        expect( dao.data.first ).to eq @data.first
        expect( dao.data.last ).to eq @data.last
    end

end
