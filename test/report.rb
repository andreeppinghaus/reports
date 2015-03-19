
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
end
