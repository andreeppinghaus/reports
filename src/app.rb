#!/usr/bin/env ruby
Encoding.default_external = Encoding::UTF_8
Encoding.default_internal = Encoding::UTF_8

require 'json'
require 'sinatra'
require 'sinatra/config_file'
require 'sinatra/mustache'
require 'sinatra/reloader' if development?
require 'fileutils'
require_relative 'lib/dao/dao'

if test? then
    set :test , true
else
    set :test , false
end

setup 'config.yml'


def create_json_file_from_base(dao,file_path,file_name)
    file_name = "#{file_name}.json" unless file_name.end_with? ".json"
    file = "#{file_path}/#{file_name}"

    if !File.exist?(file)      
        hash = dao.get_docs!(dao.base)
        File.open(file, "w"){ |f| 
            f.write(hash.to_json)
        }        
    end
end

def read_json_file_to_hash(file_path,file_name)
    file_name = "#{file_name}.json" unless file_name.end_with? ".json"
    file = "#{file_path}/#{file_name}"
    if File.exists?(file)
        hash = JSON.parse(File.read(file))
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
    dao = DAO.new "#{Sinatra::Application.settings.couchdb}"
    _hash = {}
    all_dbs = dao.get_all_databases.select{ |db| !db.end_with? "_history"} - ["_replicator","_users"]
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
