#!/usr/bin/env ruby
Encoding.default_external = Encoding::UTF_8
Encoding.default_internal = Encoding::UTF_8

require 'sinatra'
require 'sinatra/config_file'
require 'sinatra/mustache'
require 'sinatra/reloader' if development?
require 'json'
require_relative 'lib/dao/dao'
#require_relative 'lib/dao/assessment'
require_relative 'lib/dao/bioma'
require_relative 'lib/dao/dispersion'
require_relative 'lib/dao/ecology'
require_relative 'lib/dao/habitat'
require_relative 'lib/dao/phytophysiognomie'
require_relative 'lib/dao/pollination'
require_relative 'lib/dao/specie'
require_relative 'lib/dao/synonym'
#require_relative 'lib/dao/threat'
#require_relative 'lib/dao/user'

if test? then
    set :test , true
else
    set :test , false
end

setup 'config.yml'

i = Time.now
@@rows_of_document = DAO.new.get_rows_of_document
f = Time.now
puts "time to generate all rows of the base = #{f-i}"


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
    dao = ReportDAO.new
    all_dbs = dao.all_dbs.map!(&:upcase)
    view :index, {:recortes=>all_dbs} 
end


get '/reports/:db' do
    # MIssing view test.
    db =  params[:db].downcase
    file = File.read(File.expand_path("../locales/pt_report.json", __FILE__))
    reports = []
    _hash = JSON.parse(file)
    _hash.each{|k,v|
        reports.push({:name=>k,:label=>v})
    }
    view :reports, {:db=>db,:reports=>reports}
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
    dao = BiomaDAO.new(@@rows_of_document)
    dao.generate_data
    data = dao.data
    view :bioma, {:data=>data,:number_of_documents=>data.count,:db=>params[:db]}
end


get '/:db/report/name/dispersion' do
    # MIssing view test.
    dao = DispersionDAO.new(@@rows_of_document)
    dao.generate_data
    data = dao.data
    view :dispersion, {:data=>data,:number_of_documents=>data.count,:db=>params[:db]}
end


get '/:db/report/name/distribution' do
  "Missing dao and view tests."
end


get '/:db/report/name/ecology' do
    # MIssing view test.
    dao = EcologyDAO.new(@@rows_of_document)
    dao.generate_data
    data = dao.data
    view :ecology, {:data=>data,:number_of_documents=>data.count,:db=>params[:db]}
end


get '/:db/report/name/habitat' do
    # MIssing view test.
    dao = HabitatDAO.new(@@rows_of_document)
    dao.generate_data
    data = dao.data
    view :habitat, {:data=>data,:number_of_documents=>data.count,:db=>params[:db]}
end


get '/:db/report/name/occurrence' do
  "Missing dao and view tests."
end


get '/:db/report/name/phytophysiognomie' do
    # MIssing view test.
    dao = PhytophysiognomieDAO.new(@@rows_of_document)
    dao.generate_data
    data = dao.data
    view :phytophysiognomie, {:data=>data,:number_of_documents=>data.count,:db=>params[:db]}
end


get '/:db/report/name/pollination' do
    # MIssing view test.
    dao = PollinationDAO.new(@@rows_of_document)
    dao.generate_data
    data = dao.data
    view :pollination, {:data=>data,:number_of_documents=>data.count,:db=>params[:db]}
end


get '/:db/report/name/specie' do
    # MIssing view test.
    dao = SpecieDAO.new(@@rows_of_document)
    dao.generate_data
    data = dao.data
    view :specie, {:data=>data,:number_of_documents=>data.count,:db=>params[:db]}
end


get '/:db/report/name/synonym' do
    # MIssing view test.
    dao = SynonymDAO.new(@@rows_of_document)
    dao.generate_data
    data = dao.data
    view :synonym, {:data=>data,:number_of_documents=>data.count,:db=>params[:db]}
end


get '/:db/report/name/threat' do
  "Missing dao and view tests."
end


get '/:db/report/name/user' do
  "Missing dao and view tests."
end
