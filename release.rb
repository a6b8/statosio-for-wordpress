def bump( version )
    nums = version
        .split( '.' )
        .map { | n | n.to_i }
    if nums[ 2 ] == 9 and nums[ 1 ] == 9
        nums[ 0 ] = nums[ 0 ] + 1
        nums[ 1 ] = 0
        nums[ 2 ] = 0
    elsif nums[ 2 ] == 9
        nums[ 0 ]
        nums[ 1 ] = nums[ 1 ] + 1
        nums[ 2 ] = 0  
    else
        nums[ 2 ] = nums[ 2 ] + 1
    end
    return nums.join( '.' )
end


require 'zip'

hash = {
    version: {
        file: 'statosio.php',
        current: nil,
        bump: nil,
    },
    zip: {
        directory: './',
        output: nil
    }
}

index = File.read( hash[:version][:file] )
line = index
    .split( "\n" )
    .select { | l | l.start_with?( ' * Version: ' ) }[ 0 ]

hash[:version][:current] = line
    .match( /(\d+)\.(\d+)\.(\d+)/ )
    .to_s
hash[:version][:bump] = bump( hash[:version][:current] )

index_ = index
    .split( "\n" )
    .map { | n | n.eql?( line ) ? n.gsub( hash[:version][:current], hash[:version][:bump] ) : n }
    .join( "\n" )

File.open( hash[:version][:file], "w" ) { | f | f.write( index_ ) }

hash[:zip][:output] = ''
hash[:zip][:output] << './releases/statosio-'
hash[:zip][:output] << hash[:version][:bump]
hash[:zip][:output] << '.zip'

Zip::File.open( hash[:zip][:output], Zip::File::CREATE ) do | zip |
    Dir[ File.join( hash[:zip][:directory], "**", "**" ) ]
        .reject { | p | p.include?( 'release' ) }
        .each { | file | zip.add( file.sub( "#{ hash[:zip][:directory] }/", "" ), file ) }
end