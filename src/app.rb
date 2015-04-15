#!/usr/bin/env ruby
Encoding.default_external = Encoding::UTF_8
Encoding.default_internal = Encoding::UTF_8

require 'sinatra'
require 'sinatra/config_file'
require 'sinatra/mustache'
require 'sinatra/reloader' if development?
require 'json'
require 'cncflora_commons'
require_relative 'lib/dao/report'
#require_relative 'lib/dao/assessment'
require_relative 'lib/dao/bioma'
require_relative 'lib/dao/dispersion'
require_relative 'lib/dao/ecology'
require_relative 'lib/dao/habitat'
require_relative 'lib/dao/phytophysiognomie'
require_relative 'lib/dao/pollination'
require_relative 'lib/dao/specie'
require_relative 'lib/dao/synonym'
require_relative 'lib/dao/threat'
require_relative 'lib/dao/use'

if test? then
    set :test , true
else
    set :test , false
end

setup 'config.yml'


$HOST = YAML.load_file(File.expand_path('config.yml'))['development']['couchdb']
$BASE = YAML.load_file(File.expand_path('config.yml'))['development']['base_list']

i = Time.now
$ALL_DBS = ReportDAO.new($HOST,$BASE).all_dbs.map!(&:upcase)
f = Time.now
puts "Time to generate an instance of ReportDAO and, thus, all databases: = #{f-i}"


def get_reports(file="../locales/pt_report.json")
    file = File.read(File.expand_path(file, __FILE__))
    reports = []
    hash = JSON.parse(file)
    hash.each{|k,v|
        reports.push({:name=>k,:label=>v})
    }
    reports.sort_by{ |k| k[:label]}
end

$REPORTS = get_reports

def view(page,data)
    @config = settings.config
    @session_hash = {:logged => session[:logged] || false, :user => session[:user] || '{}'}

    if data[:db]
      data[:db_name] = data[:db].gsub('_',' ').upcase
    end

    mustache page, {}, @config.merge(@session_hash).merge(data)
end


get '/' do
    # MIssing view test.
    view :index, {:recortes=>$ALL_DBS}
end


get '/reports/:db' do
    # MIssing view test.
    db =  params[:db].downcase
    view :reports, {:db=>db,:reports=>$REPORTS}
end


get '/:db/report/:report' do
    # MIssing view test.
    report = params[:report]
    db = params[:db]
    redirect "/#{db}/report/name/#{report}" 
end


get '/:db/report/name/action' do
  "Missing dao and view tests."
end


get '/:db/report/name/assessment' do
  "Missing dao and view tests."
end


get '/:db/report/name/bioma' do
    # MIssing view test.
    dao = BiomaDAO.new $HOST, params[:db]
    dao.generate_data
    data = dao.data
    view :bioma, {:data=>data,:number_of_documents=>if !data.empty? then data.count else 0 end,:db=>params[:db]}
end


get '/:db/report/name/dispersion' do
    # MIssing view test.
    dao = DispersionDAO.new $HOST, params[:db]
    dao.generate_data
    data = dao.data
    view :dispersion, {:data=>data,:number_of_documents=>if !data.empty? then data.count else 0 end,:db=>params[:db]}
end


get '/:db/report/name/distribution' do
  "Missing dao and view tests."
end


get '/:db/report/name/ecology' do
    # MIssing view test.
    dao = EcologyDAO.new $HOST, params[:db]
    dao.generate_data
    data = dao.data
    view :ecology, {:data=>data,:number_of_documents=>if !data.empty? then data.count else 0 end,:db=>params[:db]}
end


get '/:db/report/name/habitat' do
    # MIssing view test.
    dao = HabitatDAO.new $HOST, params[:db]
    dao.generate_data
    data = dao.data
    view :habitat, {:data=>data,:number_of_documents=>if !data.empty? then data.count else 0 end,:db=>params[:db]}
end


get '/:db/report/name/occurrence' do
  "Missing dao and view tests."
end


get '/:db/report/name/phytophysiognomie' do
    # MIssing view test.
    dao = PhytophysiognomieDAO.new $HOST, params[:db]
    dao.generate_data
    data = dao.data
    view :phytophysiognomie, {:data=>data,:number_of_documents=>if !data.empty? then data.count else 0 end,:db=>params[:db]}
end


get '/:db/report/name/pollination' do
    # MIssing view test.
    dao = PollinationDAO.new $HOST, params[:db]
    dao.generate_data
    data = dao.data
    view :pollination, {:data=>data,:number_of_documents=>if !data.empty? then data.count else 0 end,:db=>params[:db]}
end


get '/:db/report/name/specie' do
    # MIssing view test.
    dao = SpecieDAO.new $HOST, params[:db]
    dao.generate_data
    data = dao.data
    view :specie, {:data=>data,:number_of_documents=>if !data.empty? then data.count else 0 end,:db=>params[:db]}
end


get '/:db/report/name/synonym' do
    # MIssing view test.
    dao = SynonymDAO.new $HOST, params[:db]
    dao.generate_data
    data = dao.data
    view :synonym, {:data=>data,:number_of_documents=>if !data.empty? then data.count else 0 end,:db=>params[:db]}
end


get '/:db/report/name/threat' do
    dao = ThreatDAO.new $HOST, params[:db]
    dao.generate_data
    data = dao.data
    view :threat, {:data=>data,:number_of_documents=>if !data.empty? then data.count else 0 end,:db=>params[:db]}
end


get '/:db/report/name/use' do
    # MIssing view test.
    dao = UseDAO.new $HOST, params[:db]
    dao.generate_data
    data = dao.data
    view :use, {:data=>data,:number_of_documents=>if !data.empty? then data.count else 0 end,:db=>params[:db]}
end
