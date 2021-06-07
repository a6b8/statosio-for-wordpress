require 'zip'
require 'set'

version = File.read( 'statosio.php' )
    .split("\n")
    .select { | l | l.start_with?(' * Version: ') }[ 0 ]
    .match( /(\d+)\.(\d+)\.(\d+)/ )

puts version

paths = Dir[ "./**/*.*" ]
    .reject { | a | a.include?('release') }
    .to_set
    .to_a


path = "./releases/test.zip"
Zip::File.open( path, Zip::File::CREATE ) do | zipfile |
    paths.each do | path |
        item = {}
        item[:name] = File.basename( path )   
        item[:folder] = ''
        puts 'Here'
        puts item
        puts path
        zipfile.add( item[:name], path )
        puts 'THERE'
    end
    zipfile.get_output_stream("myFile") { |f| f.write "myFile contains just this" }
end