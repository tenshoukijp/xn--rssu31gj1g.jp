my $filename = $ARGV[0];
if ($filename =~ /\bVS_/i) {
	print "error";
	exit;
}

# �G��.net�T�C�g�ւ�FTP
if ($filename =~ /index_HM/i) {
	print "upload start...\n";
	$cmd = sprintf('cmd /C C:\usr\nextftp\NEXTFTP.EXE $Host16 -local="C:\usr\web\�V�ċLjp" -upload=%s -quit -nosound -minimize', $filename );
	print $cmd . "\n";
	print `$cmd`;

	print "upload complete!\n";

# �V�ċL.jp�T�C�g�ւ�FTP
} else {
	print "upload start...\n";
	$cmd = sprintf('cmd /C C:\usr\nextftp\NEXTFTP.EXE $Host18 -local="C:\usr\web\�V�ċLjp" -upload=%s -quit -nosound -minimize', $filename );
	print $cmd . "\n";
	print `$cmd`;

	print "upload complete!\n";
}
