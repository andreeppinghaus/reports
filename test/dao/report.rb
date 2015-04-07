require 'rspec'
require 'yaml'
require_relative File.expand_path("src/lib/dao/dao")
require_relative File.expand_path("src/lib/dao/report")


describe "ReportDAO" do

    before(:all){
        @config = YAML.load_file(File.expand_path('config.yml'))["test"]
        @host = @config["couchdb"] 
        @base = @config["base_list"]

        @all_dbs = [
            "endemicas_rio_de_janeiro", 
            "especies_indicadas", 
            "livro_vermelho", 
            "pan_bacia_alto_tocantins", 
            "pan_espinhaco_meridional", 
            "pan_grao_mogol", 
            "plantas_raras_do_cerrado"
        ]

        @metadata_types = {
            "assessment" => {"number_of_docs" => 605},
            "occurrence" => {"number_of_docs" => 14346},
            "profile" => {"number_of_docs" => 615},
            "taxon" => {"number_of_docs" => 634}
        }

        @keys_doc = ["total_rows", "offset", "rows"]

        @dao = ReportDAO.new( DAO.new.get_rows_of_document )
    }

    it "Should be an instance of the ReportDAO class." do
        dao = ReportDAO.new
        expect( dao ).to be_an_instance_of ReportDAO
        expect( ReportDAO.superclass ).to eq DAO 
        expect( dao.host ).to eq @host
        expect( dao.base ).to eq @base
        expect( dao.rows_of_document ).to eq nil
        expect( dao.docs_by_metadata_types ).to be_a Hash
        expect( dao.docs_by_metadata_types.empty? ).to be true
        expect( dao.metadata_types ).to be_a Array
        expect( dao.metadata_types ).to eq @metadata_types.keys
        expect( dao.hash_fields).to be_a Hash
        expect( dao.hash_fields.empty?).to be true
    end


    it "Should get all databases except: '_replicator','_users' and those ending with '_history'." do
        dao = ReportDAO.new
        expect( dao.all_dbs ).to eq @all_dbs
    end


    it "Should get documents by metadata types." do
        expect( @dao.docs_by_metadata_types.empty? ).to be true
        @dao.set_docs_by_metadata_types
        expect( @dao.docs_by_metadata_types.keys.sort ).to eq @metadata_types.keys
    end


    it "Should get all assessment documents." do
        assessment = @dao.docs_by_metadata_types["assessment"]
        expect( assessment.count ).to eq @metadata_types["assessment"]["number_of_docs"]
        expect( assessment[ rand(assessment.size) ]["doc"]["metadata"]["type"] ).to eq "assessment"
    end


    it "Should get all occurrence documents." do
        occurrence = @dao.docs_by_metadata_types["occurrence"]
        expect( occurrence.count ).to eq @metadata_types["occurrence"]["number_of_docs"]
        expect( occurrence[ rand(occurrence.size) ]["doc"]["metadata"]["type"] ).to eq "occurrence"
    end


    it "Should get all profile  documents." do
        profile = @dao.docs_by_metadata_types["profile"]
        expect( profile.count ).to eq @metadata_types["profile"]["number_of_docs"]
        expect( profile[ rand(profile.size) ]["doc"]["metadata"]["type"] ).to eq "profile"
    end


    it "Should get all taxon documents." do
        taxon = @dao.docs_by_metadata_types["taxon"]
        expect( taxon.count ).to eq @metadata_types["taxon"]["number_of_docs"]
        expect( taxon[ rand(taxon.size) ]["doc"]["metadata"]["type"] ).to eq "taxon"
    end
end
