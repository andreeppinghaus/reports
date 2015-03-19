ENV['RACK_ENV'] = 'test'

require_relative '../src/app'

require 'cncflora_commons'
require 'rspec'
require 'rack/test'
require 'rspec-html-matchers'
require 'uri'

include Rack::Test::Methods

def app
    Sinatra::Application
end

RSpec.configure do |config|
  config.include RSpecHtmlMatchers
end
