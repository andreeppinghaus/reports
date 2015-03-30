require 'rspec'
require 'yaml'
require_relative File.expand_path("src/lib/dao/dao.rb")


describe "DAO" do

    before(:all){
        @config = YAML.load_file(File.expand_path('config.yml'))["test"]
        @uri = @config["couchdb"] 
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
        expect(dao.metadata_types).to eq({:assessment=>"assessment",:occurrence=>"occurrence",:profile=>"profile",:taxon=>"taxon"}) 

    end


    it "Should get all databases except '_replicator','_users' and those ending with '_history'." do
        dao = DAO.new
        all_dbs = dao.get_all_databases
        history = all_dbs.select{ |d| d.end_with? "_history"}
        others = ["_replicator","_users"]
        db = all_dbs - (history+others)
        expect(db.count).to eq( (all_dbs-history-others).count )
        expect(db).to include("livro_vermelho","pan_bacia_alto_tocantins","plantas_raras_do_cerrado")
    end

    it "Should check docs of a database" do
        dao = DAO.new @base_list 
        dao.generate_docs!(dao.base)
        first_doc = dao.docs["rows"].first["doc"]
        last_doc = dao.docs["rows"].last["doc"]

        expect(dao.docs["total_rows"]).to eq dao.docs["rows"].count

        expect(first_doc.keys).to include "metadata"
        expect(first_doc["metadata"].keys).to include "type"
        expect(@types).to include first_doc["metadata"]["type"]

        expect(last_doc.keys).to include "metadata"
        expect(last_doc["metadata"].keys).to include "type"
        expect(@types).to include last_doc["metadata"]["type"]
    end


    it "Should get docs by metadata type assessment" do
        dao = DAO.new @base_list
        docs = dao.get_docs_by_metadata_type(dao.base,dao.metadata_types[:assessment])
        first = docs.first["metadata"]["type"]
        last = docs.last["metadata"]["type"]
        expect(first).to eq('assessment')
        expect(last).to eq('assessment')
    end


    it "Should get docs by metadata type occurrence" do
        dao = DAO.new @base_list
        docs = dao.get_docs_by_metadata_type(dao.base,dao.metadata_types[:occurrence])
        first = docs.first["metadata"]["type"]
        last = docs.last["metadata"]["type"]
        expect(first).to eq('occurrence')
        expect(last).to eq('occurrence')
    end


    it "Should get docs by metadata type profile" do
        dao = DAO.new @base_list
        docs = dao.get_docs_by_metadata_type(dao.base,dao.metadata_types[:profile])
        first = docs.first["metadata"]["type"]
        last = docs.last["metadata"]["type"]
        expect(first).to eq('profile')
        expect(last).to eq('profile')
    end


    it "Should get docs by metadata type taxon" do
        dao = DAO.new @base_list
        docs = dao.get_docs_by_metadata_type(dao.base,dao.metadata_types[:taxon])
        first = docs.first["metadata"]["type"]
        last = docs.last["metadata"]["type"]
        expect(first).to eq('taxon')
        expect(last).to eq('taxon')
    end

    it "Should generate list of hash docs" do
        dao = DAO.new @base_list
        list_of_hash_docs = dao.generate_list_of_hash_docs
        expect(list_of_hash_docs.empty?).to be true
        dao.generate_docs!(dao.base)
        list_of_hash_docs = dao.generate_list_of_hash_docs
        expect(list_of_hash_docs.empty?).to be false
        expect(list_of_hash_docs.first.keys).to include "doc"
        expect(list_of_hash_docs.last.keys).to include "doc"
    end

    it "Should generate data list of all metadata types." do
        dao = DAO.new @base_list
        hash = dao.generate_data_lists_by_metadata_type(@types)
        expect(hash.count).to eq 4
        expect(hash["assessment"].first["metadata"]["type"]).to eq("assessment")
        expect(hash["assessment"].last["metadata"]["type"]).to eq("assessment")
        expect(hash["occurrence"].first["metadata"]["type"]).to eq("occurrence")
        expect(hash["occurrence"].last["metadata"]["type"]).to eq("occurrence")
        expect(hash["profile"].first["metadata"]["type"]).to eq("profile")
        expect(hash["profile"].last["metadata"]["type"]).to eq("profile")
        expect(hash["taxon"].first["metadata"]["type"]).to eq("taxon")
        expect(hash["taxon"].last["metadata"]["type"]).to eq("taxon")
    end


end
