ENV['RACK_ENV'] = 'test'


require 'cncflora_commons'
require 'rspec'
require_relative '../../src/dao/dao'


describe "DAO" do

    before(:all){
        @uri = "http://localhost:5984"
        @dao = DAO.new(@uri)
        @base_list = "livro_vermelho_2013"
        @types = ["taxon", "assessment", "occurrence"]
    }

    it "Should be a instance of a DAO" do
        dao = DAO.new(@uri)
        expect(dao).to be_a DAO
        expect(dao.uri).to eq(@uri)
    end


    it "Should get all databases except '_replicator','_users' and those ending with '_history'." do
        all_dbs = @dao.get_all_databases
        history = all_dbs.select{ |d| d.end_with? "_history"}
        others = ["_replicator","_users"]
        db = all_dbs - (history+others)
        expect(db.count).to eq( (all_dbs-history-others).count )
        expect(db).to include("livro_vermelho_2013","pan_bacia_alto_tocantins")
    end

    it "Should check docs of a database" do      
        docs = @dao.get_docs!(@base_list)
        first_doc = docs["rows"].first["doc"]
        last_doc = docs["rows"].last["doc"]

        expect(docs["total_rows"].to_i).to eq docs["rows"].count

        expect(first_doc.keys).to include "metadata"
        expect(first_doc["metadata"].keys).to include "type"
        expect(@types).to include first_doc["metadata"]["type"]

        expect(last_doc.keys).to include "metadata"
        expect(last_doc["metadata"].keys).to include "type"
        expect(@types).to include last_doc["metadata"]["type"]
    end

end
