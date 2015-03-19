#!/usr/bin/env ruby
Encoding.default_external = Encoding::UTF_8
Encoding.default_internal = Encoding::UTF_8

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

#puts "databases = #{get_databases}"

def view(page,data)
    @config = settings.config
    @session_hash = {:logged => session[:logged] || false, :user => session[:user] || '{}'}

    if data[:db]
      data[:db_name] = data[:db].gsub('_',' ').upcase
    end

    mustache page, {}, @config.merge(@session_hash).merge(data)
end

get '/' do
    "Hello"
end

