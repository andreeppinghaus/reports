############################################################################
#
# These are the Initial tests. These must be reviewed and, thus, refactored.
#
############################################################################
require_relative 'base'


describe "Generation of reports." do

    #before(:each) do
    #  post "/login", { :user=>'{"name":"Bruno","email":"bruno@cncflora.net"}' }
    #end

    it "Check all databases." do
        all_dbs = get_databases
        history = all_dbs.select{ |d| d.end_with? "_history"}
        others = ["_replicator","_users"]
        db = all_dbs - (history+others)

        expect(db.count).to eq( (all_dbs-history-others).count )
    end

    it "Get index page." do
        uri = "http://localhost:5984"
        all_dbs = get_databases
        history = all_dbs.select{ |d| d.end_with? "_history"}
        others = ["_replicator","_users"]
        db = all_dbs - (history+others)

        get "/"
        expect(last_response.body).to have_tag('th',:text=>'RECORTES')
        db.each{ |d|
            expect(last_response.body).to have_tag('tbody tr td a',:href=>"#{uri}/reports/#{d}",:text=>d.upcase)
        }
    end

    it "Get the reports of a checklist" do
        uri = "http://localhost:5984"
        all_dbs = get_databases
        history = all_dbs.select{ |d| d.end_with? "_history"}
        others = ["_replicator","_users"]
        db = all_dbs - (history+others)
        reports = get_reports_names

        get "/reports/#{db[0]}"
        expect(last_response.body).to have_tag('div h2',:text=>"Relatórios: #{db[0]}")
        reports.each{ |report|
            expect(last_response.body).to have_tag('ul li a',:href=>"#{uri}/#{db[0]}/#{report[:name]}",:text=>"#{report[:label]}")
        }
    end
end
