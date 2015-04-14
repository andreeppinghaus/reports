require 'rspec'
require 'yaml'
require_relative File.expand_path("src/lib/dao/dao")


describe "DAO" do

    before(:all){
        @config = YAML.load_file(File.expand_path('config.yml'))["test"]
        @host = @config["couchdb"]
        @base = @config["base_list"]
        @documents_number_of_base = 16200
        @keys_of_rows = ["id", "key", "value", "doc"]
        @all_dbs =[
            "_replicator", 
            "_users", 
            "endemicas_rio_de_janeiro", 
            "endemicas_rio_de_janeiro_history", 
            "especies_indicadas", 
            "especies_indicadas_history", 
            "livro_vermelho", 
            "livro_vermelho_2013_history", 
            "pan_bacia_alto_tocantins", 
            "pan_bacia_alto_tocantins_history", 
            "pan_espinhaco_meridional", 
            "pan_espinhaco_meridional_history", 
            "pan_grao_mogol", 
            "pan_grao_mogol_history", 
            "plantas_raras_do_cerrado", 
            "plantas_raras_do_cerrado_history"
        ]
    }


    it "Should be an instance of the DAO class." do
        dao = DAO.new @host, @base
        expect( dao ).to be_a DAO
        expect( dao.host ).to eq @host
        expect( dao.base ).to eq @base
    end


    it "Should get all databases." do
        dao = DAO.new @host, @base
        expect( dao.all_dbs ).to eq @all_dbs
    end


    it "Should get all documents of the '@base' database." do
        dao = DAO.new @host, @base
        rows = dao.get_rows_of_document
        expect( rows ).to be_a Array
        expect( rows.count ).to eq @documents_number_of_base
        expect( rows[ rand(rows.size)].keys ).to eq @keys_of_rows
    end

end
