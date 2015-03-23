#!/usr/bin/env ruby
Encoding.default_external = Encoding::UTF_8
Encoding.default_internal = Encoding::UTF_8

require 'json'
require 'sinatra'
require 'sinatra/config_file'
require 'sinatra/mustache'
require 'sinatra/reloader' if development?
require 'cncflora_commons'

if test? then
    set :test , true
else
    set :test , false
end

setup 'config.yml'


def get_databases
    all_dbs = http_get("#{Sinatra::Application.settings.couchdb}/_all_dbs")
end

def get_docs(base)
    uri = "#{Sinatra::Application.settings.couchdb}/#{base}/_all_docs?include_docs=true"
    docs = http_get(uri)
end

def create_report_base(path,name,extension)
    file = "#{path}/#{name}.#{extension}"
    if !File.exists?(file)
        hash = get_docs(name)
        File.open(file,"w") do |f|
            f.write(hash.to_json)
        end
    end
    file
end

def read_json_to_hash(path,name,extension)
    file = "#{path}/#{name}.#{extension}"
    if File.exists?(file)
        hash = JSON.parse(File.read(file))
    else
       #handle exception 
    end
    hash
end

def get_reports_names
    file = File.read(File.expand_path("../locales/pt_report.json", __FILE__))
    reports = []
    _hash = JSON.parse(file)
    _hash.each{|k,v|
        reports.push({:name=>k,:label=>v})
    }
    reports
end



def view(page,data)
    @config = settings.config
    @session_hash = {:logged => session[:logged] || false, :user => session[:user] || '{}'}

    if data[:db]
      data[:db_name] = data[:db].gsub('_',' ').upcase
    end

    mustache page, {}, @config.merge(@session_hash).merge(data)
end

get '/' do
    _hash = {}
    all_dbs = get_databases.select{ |db| !db.end_with? "_history"} - ["_replicator","_users"]
    all_dbs.map!(&:upcase)
    view :index, {:recortes=>all_dbs} 
end

get '/reports/:db' do
    db =  params[:db].downcase
    file = File.read(File.expand_path("../locales/pt_report.json", __FILE__))
    reports = []
    _hash = JSON.parse(file)
    _hash.each{|k,v|
        reports.push({:name=>k,:label=>v})
    }
    view :reports, {:db=>db,:reports=>reports}
end

