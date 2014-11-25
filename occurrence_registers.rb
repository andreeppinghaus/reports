require 'couchdb_basic'
require 'csv'

db = Couchdb.new( "http://cncflora.jbrj.gov.br/datahub/plantas_raras_cerrado" )

docs = db.get_all()
puts docs.count

keys = [:occurrenceID,:bibliographicCitation,:institutionCode,:collectionCode,:catalogNumber,:recordNumber,:recordedBy,:occurrenceRemarks,:year,:month,:day,:identifiedBy,:yearIdentified,:monthIdentified,:dayIdentified,:stateProvince,:municipality,:locality,:decimalLatitude,:decimalLongitude,:family,:genus,:specificEpithet,:infraspecificEpithet,:scientificName,:georeferenceRemarks,:georeferenceProtocol,:georeferenceVerificationStatus,:georeferencedBy,:georeferencedDate,:georeferencePrecision,:acceptedNameUsage]
#keys = [:occurrenceID,:decimalLatitude,:decimalLongitude,:acceptedNameUsage]
        
no_metadata = []
occurrences = []

spps = []

docs.each{ |doc|
    if doc[:metadata][:type] == 'taxon'
        if doc[:taxonomicStatus] == 'accepted'
            doc[:synonyms] = []
            spps << doc
        end
    end
}

puts spps.count

docs.each{ |doc|
    if doc[:metadata][:type] == 'taxon'
       if doc[:taxonomicStatus] == 'synonym'
           spps.each {|spp|
               if spp[:scientificName] == doc[:acceptedNameUsage] || spp[:scientificNameWithoutAuthorship] == doc[:acceptedNameUsage]
                   spp[:synonyms] << doc
               end
           }
        end
    end
}

names = {}

spps.each{|spp|
    names[spp[:acceptedNameUsage]] = spp[:scientificNameWithoutAuthorship]
    names[spp[:scientificName]] = spp[:scientificNameWithoutAuthorship]
    names[spp[:scientificNameWithoutAuthorship]] = spp[:scientificNameWithoutAuthorship]
    spp[:synonyms].each{|syn|
        names[syn[:acceptedNameUsage]] = spp[:scientificNameWithoutAuthorship]
        names[syn[:scientificName]] = spp[:scientificNameWithoutAuthorship]
        names[syn[:scientificNameWithoutAuthorship]] = spp[:scientificNameWithoutAuthorship]
    }
}

invalid = []
no_geo = []
invalid_geo = []
bad_name = []

i=0
docs.each{ |doc|
    if doc[:metadata] && doc[:metadata][:type] 
        if doc[:metadata][:type] == "occurrence"
            i = i + 1;

            doc[:acceptedNameUsage] = names[doc[:scientificName]] || names[doc[:acceptedNameUsage]]

            if doc[:acceptedNameUsage].nil? || doc[:acceptedNameUsage] == '' then
                bad_name << doc[:_id]
            elsif doc.has_key?(:georeferenceVerificationStatus) 
                if doc[:georeferenceVerificationStatus] == "1" || doc[:georeferenceVerificationStatus] == "ok" then
                    if doc[:validation]
                        if doc[:validation][:status]  && doc[:validation][:status] != ""
                            if doc[:validation][:status] == "valid"
                                occurrences << doc
                            else
                                invalid << doc[:_id]
                            end
                        else
                            if (!doc[:validation].has_key?(:taxonomy) || doc[:validation][:taxonomy].nil? || doc[:validation][:taxonomy] == 'valid') && 
                            (doc[:validation][:georeference].nil? || doc[:validation][:georeference] == 'valid') &&
                            (doc[:validation][:native] != 'non-native') &&
                            (doc[:validation][:presence] != 'absent') &&
                            (doc[:validation][:cultivated] != 'yes') &&
                            (doc[:validation][:duplicated] != 'yes')
                                occurrences << doc
                            else
                                invalid << doc[:_id]
                            end
                        end
                    else
                        occurrences << doc
                    end
                else
                    invalid_geo << doc[:_id]
                end
            else
                no_geo << doc[:_id]
            end
        else
            # not and occ
        end
    else
        # It Doesn't have metadata field.
        no_metadata << doc[:_id]
    end
}

puts "bad_name #{bad_name.count}; invalid #{invalid.count}; no_geo #{no_geo.count} ;  bad_geo #{invalid_geo.count}"
puts "occurrences loaded #{occurrences.count} #{i}."

keys = keys.uniq

occurrences.each{ |o|
    _keys = keys - o.keys
    _keys.each{ |k|
        o[k] = ""
    }
}
puts "occurrences fields loaded."
puts "no_metadata = #{no_metadata}"
puts "keys = #{keys}"

CSV.open("occurrence_registers.csv", "wb", col_sep: ';') do |csv|
    csv << keys # adds the attributes name on the first line
    occurrences.each do |occurrence|
        data = []
        keys.each {|k| data << occurrence[k] }
        csv << data
   end
end

puts "finished."
