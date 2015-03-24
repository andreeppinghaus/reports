############################################################################
#
# These are the Initial tests. These must be reviewed and, thus, refactored.
#
############################################################################
require_relative 'base'
require_relative 'dao/dao'
require_relative '../src/app'


describe "Generation of reports." do

    #before(:each) do
    #  post "/login", { :user=>'{"name":"Bruno","email":"bruno@cncflora.net"}' }
    #end

    before(:all){

        @uri = "http://localhost:5984"
        @dao = DAO.new(@uri)
        @base_file_path = "data"
        @base_file_name = "livro_vermelho_2013"
    }



    it "Check for the base file the reports to be generated." do
        dao = DAO.new(@uri,@base_file_name)
        create_json_file_from_base(dao,@base_file_path,@base_file_name)
        path = File.expand_path("#{@base_file_path}/#{@base_file_name}.json")
        expect(File.exist?(path)).to be(true)
        #File.delete(report_base) if File.exist?(report_base)
    end

    it "Read base report json file to hash." do
        base_hash = read_json_file_to_hash(@base_file_path,@base_file_name)
        expect(base_hash["total_rows"]).to eq(base_hash["rows"].count)
    end

    it "Generate taxons list."


    it "Get index page." do
        all_dbs = @dao.get_all_databases
        history = all_dbs.select{ |d| d.end_with? "_history"}
        others = ["_replicator","_users"]
        db = all_dbs - (history+others)

        get "/"
        expect(last_response.body).to have_tag('th',:text=>'RECORTES')
        db.each{ |d|
            expect(last_response.body).to have_tag('tbody tr td a',:href=>"#{@uri}/reports/#{d}",:text=>d.upcase)
        }
    end

    it "Get the reports of a checklist" do
        all_dbs = @dao.get_all_databases
        history = all_dbs.select{ |d| d.end_with? "_history"}
        others = ["_replicator","_users"]
        db = all_dbs - (history+others)
        reports = get_reports_names

        get "/reports/#{db[0]}"
        expect(last_response.body).to have_tag('div h2',:text=>"Relatórios: #{db[0]}")
        reports.each{ |report|
            expect(last_response.body).to have_tag('ul li a',:href=>"#{@uri}/#{db[0]}/#{report[:name]}",:text=>"#{report[:label]}")
        }
    end
end
