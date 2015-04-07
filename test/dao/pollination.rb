require 'rspec'
require 'yaml'
require_relative File.expand_path("src/lib/dao/dao")
require_relative File.expand_path("src/lib/dao/pollination")

describe "PollinationDAO" do

    before(:all){
        @config = YAML.load_file(File.expand_path('config.yml'))["test"]
        @host = @config["couchdb"] 
        @base = @config["base_list"]
        @metadata_types = { "profile" => { "reproduction" => { "pollinationSyndrome" => 21 }  } }
        @hash_fields = {
            :id => "",
            :family => "",
            :scientificNameWithoutAuthorship  => "",
            :pollination => ""
        }
        @data = [
            {
                :id=>"urn:lsid:cncflora.jbrj.gov.br:profile:justicia:clivalis:1374083792", 
                :family=>"ACANTHACEAE", :scientificNameWithoutAuthorship=>"Justicia clivalis", :pollination=>"ornitophily"
            },
            {
                :id=>"urn:lsid:cncflora.jbrj.gov.br:profile:vochysia:pygmaea:1381429089", 
                :family=>"VOCHYSIACEAE", :scientificNameWithoutAuthorship=>"Vochysia pygmaea", :pollination=>"melitophily"
            }
        ]
    } 


    it "Should be an instance of the PollinationDAO class." do
        dao = PollinationDAO.new
        expect( dao ).to be_a PollinationDAO
        expect( PollinationDAO.superclass ).to eq ReportDAO
        expect( dao.host ).to eq @host
        expect( dao.base ).to eq @base
        expect( dao.rows_of_document ).to eq nil
        expect( dao.docs_by_metadata_types ).to be_a Hash
        expect( dao.docs_by_metadata_types.empty? ).to be true
        expect( dao.metadata_types ).to be_a Array
        expect( dao.metadata_types[0] ).to eq @metadata_types.keys[0]
        expect( dao.hash_fields).to eq @hash_fields
        expect( dao.data ).to be_a Array
        expect( dao.data.empty? ).to be true
    end


    it "Should generate data of the pollinations report." do      
        dao = PollinationDAO.new( DAO.new.get_rows_of_document )
        expect(dao.data.empty?).to be true
        dao.generate_data        
        expect( dao.data.count ).to eq @metadata_types["profile"]["reproduction"]["pollinationSyndrome"]
        expect( dao.data.first ).to eq @data.first
        expect( dao.data.last ).to eq @data.last
    end

end
