require 'rspec'
require 'yaml'
require_relative File.expand_path("src/lib/dao/dao.rb")


describe "DAO" do

    before(:all){
        @config = YAML.load_file(File.expand_path('config.yml'))["test"]
        @uri = @config["couchdb"] 
        @dao = DAO.new
        @base_list = @config["base_list"] 
        @types = ["assessment","occurrence","profile","taxon"]
    }


    it "Should be a instance of a DAO" do
        dao = DAO.new
        expect(dao).to be_a DAO
        expect(dao.uri).to eq(@uri)
        expect(dao.base).to eq('_all_dbs')
        expect(dao.docs).to be_an_instance_of Hash
        expect(dao.docs.empty?).to be true
    end


    it "Should get all databases except '_replicator','_users' and those ending with '_history'." do
        all_dbs = @dao.get_all_databases
        history = all_dbs.select{ |d| d.end_with? "_history"}
        others = ["_replicator","_users"]
        db = all_dbs - (history+others)
        expect(db.count).to eq( (all_dbs-history-others).count )
        expect(db).to include("livro_vermelho","pan_bacia_alto_tocantins","plantas_raras_do_cerrado")
    end


    it "Should check docs of a database" do      
        @dao.get_docs!(@base_list)
        first_doc = @dao.docs["rows"].first["doc"]
        last_doc = @dao.docs["rows"].last["doc"]

        expect(@dao.docs["total_rows"].to_i).to eq @dao.docs["rows"].count

        expect(first_doc.keys).to include "metadata"
        expect(first_doc["metadata"].keys).to include "type"
        expect(@types).to include first_doc["metadata"]["type"]

        expect(last_doc.keys).to include "metadata"
        expect(last_doc["metadata"].keys).to include "type"
        expect(@types).to include last_doc["metadata"]["type"]
    end

    it "Should get docs by metadata type" do
        docs = @dao.get_docs_by_metadata_type(@base_list,'profile')
        docs.each{ |r|
            expect(r["metadata"]["type"]).to eq('profile')
        }          
    end

end
