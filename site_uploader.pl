my $filename = $ARGV[0];
if ($filename =~ /\bVS_/i) {
	print "error";
	exit;
}

# 秀丸.netサイトへのFTP
if ($filename =~ /index_HM/i) {
	print "upload start...\n";
	$cmd = sprintf('cmd /C C:\usr\nextftp\NEXTFTP.EXE $Host16 -local="C:\usr\web\天翔記jp" -upload=%s -quit -nosound -minimize', $filename );
	print $cmd . "\n";
	print `$cmd`;

	print "upload complete!\n";

# 天翔記.jpサイトへのFTP
} else {
	print "upload start...\n";
	$cmd = sprintf('cmd /C C:\usr\nextftp\NEXTFTP.EXE $Host18 -local="C:\usr\web\天翔記jp" -upload=%s -quit -nosound -minimize', $filename );
	print $cmd . "\n";
	print `$cmd`;

	print "upload complete!\n";
}
