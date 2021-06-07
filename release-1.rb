require 'zip'

hash = {
    directory: './',
    output: nil
}

hash[:output] = ''
hash[:output] << './releases/statosio-'
hash[:output] << File.read( 'statosio.php' )
    .split("\n")
    .select { | l | l.start_with?(' * Version: ') }[ 0 ]
    .match( /(\d+)\.(\d+)\.(\d+)/ )
    .to_s
hash[:output] << '.zip'

File.delete( hash[:output] ) if File.exist?( hash[:output] )
Zip::File.open( hash[:output], Zip::File::CREATE ) do | zip |
    Dir[ File.join( hash[:directory], "**", "**" ) ]
    .reject { | p | p.include?( 'release' ) }
    .each { | file | zip.add( file.sub( "#{ hash[:directory] }/", "" ), file ) }
end