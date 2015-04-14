require 'rspec'
require 'yaml'
require_relative File.expand_path("src/lib/dao/report")


describe "ReportDAO" do

    before(:all){
        @config = YAML.load_file(File.expand_path('config.yml'))["test"]
        @host = @config["couchdb"] 

        @all_dbs = {
            "endemicas_rio_de_janeiro" => { 
                "name" => "endemicas_rio_de_janeiro", 
                "total_rows_of_documents" => 13301,
                "total_metadata_types" => {
                    "assessment" => 1,
                    "occurrence" => 11379,
                    "profile" => 128,
                    "taxon" => 1793 
                }
            } ,
            "especies_indicadas" => { 
                "name" => "especies_indicadas", "total_rows_of_documents" => 2,                
                "total_metadata_types" => {
                    "assessment" => 0,
                    "occurrence" => 0,
                    "profile" => 0,
                    "taxon" => 2 
                }
            },
            "livro_vermelho" => { 
                "name" => "livro_vermelho", "total_rows_of_documents" => 109637, 
                "total_metadata_types" => {
                    "assessment" => 4617,
                    "occurrence" => 89306,
                    "profile" => 0,
                    "taxon" => 15714 
                }
            },
            "pan_bacia_alto_tocantins" => { 
                "name" => "pan_bacia_alto_tocantins", "total_rows_of_documents" => 730,
                "total_metadata_types" => {
                    "assessment" => 0,
                    "occurrence" => 705,
                    "profile" => 0,
                    "taxon" => 25
                }
            },
            "pan_espinhaco_meridional" => { 
                "name" => "pan_espinhaco_meridional", "total_rows_of_documents" => 8389,
                "total_metadata_types" => {
                    "assessment" => 0,
                    "occurrence" => 8273,
                    "profile" => 0,
                    "taxon" => 116 
                }
            },
            "pan_grao_mogol" => { 
                "name" => "pan_grao_mogol", "total_rows_of_documents" => 283,
                "total_metadata_types" => {
                    "assessment" => 0,
                    "occurrence" => 259,
                    "profile" => 0,
                    "taxon" => 24 
                }
            },
            "plantas_raras_do_cerrado" => { 
                "name" => "plantas_raras_do_cerrado", "total_rows_of_documents" => 16200,
                "total_metadata_types" => {
                    "assessment" => 605,
                    "occurrence" => 14346,
                    "profile" => 615,
                    "taxon" => 634 
                }
            }
        }


        @keys_doc = ["total_rows", "offset", "rows"]

    }

    it "Should be an instance of the ReportDAO class." do
        dao = ReportDAO.new @host,@all_dbs["especies_indicadas"]["name"]
        expect( dao ).to be_an_instance_of ReportDAO
        expect( dao ).not_to be_an_instance_of DAO 
        expect( dao ).to be_kind_of DAO 
        expect( dao.host ).to eq @host
        expect( dao.base ).to eq @all_dbs["especies_indicadas"]["name"]
        expect( dao.rows_of_document.count ).to eq @all_dbs["especies_indicadas"]["total_rows_of_documents"]
        expect( dao.docs_by_metadata_types ).to be_a Hash
        expect( dao.docs_by_metadata_types ).to be_empty
        expect( dao.metadata_types ).to be_a Array
        expect( dao.metadata_types ).to eq ["assessment","occurrence","profile","taxon"]
        expect( dao.hash_fields ).to be_a Hash
        expect( dao.hash_fields ).to be_empty
    end


    it "Should get all databases except: '_replicator','_users' and those ending with '_history'." do
        dao = ReportDAO.new @host, @all_dbs["especies_indicadas"]["name"]
        expect( dao.all_dbs ).to eq @all_dbs.keys
    end


    it "Should get documents by metadata types from the database 'plantas_raras_do_cerrado'." do
        plantas_raras = @all_dbs["plantas_raras_do_cerrado"]
        dao = ReportDAO.new @host, plantas_raras["name"]
        expect( dao.docs_by_metadata_types ).to be_empty 
        dao.set_docs_by_metadata_types
        expect( dao.docs_by_metadata_types["assessment"].count ).to eq plantas_raras["total_metadata_types"]["assessment"] 
        expect( dao.docs_by_metadata_types["occurrence"].count ).to eq plantas_raras["total_metadata_types"]["occurrence"] 
        expect( dao.docs_by_metadata_types["profile"].count ).to eq plantas_raras["total_metadata_types"]["profile"] 
        expect( dao.docs_by_metadata_types["taxon"].count ).to eq plantas_raras["total_metadata_types"]["taxon"] 
    end


    it "Should get documents by metadata types from the database 'pan_grao_mogol'." do
        pan_grao = @all_dbs["pan_grao_mogol"]
        dao = ReportDAO.new @host, pan_grao["name"]
        expect( dao.docs_by_metadata_types ).to be_empty 
        dao.set_docs_by_metadata_types
        expect( dao.docs_by_metadata_types ).not_to have_key "assessment" 
        expect( dao.docs_by_metadata_types["occurrence"].count ).to eq pan_grao["total_metadata_types"]["occurrence"] 
        expect( dao.docs_by_metadata_types ).not_to have_key "profile" 
        expect( dao.docs_by_metadata_types["taxon"].count ).to eq pan_grao["total_metadata_types"]["taxon"] 
    end

end
