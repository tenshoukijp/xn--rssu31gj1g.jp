mkdir "dst";

# .iniファイルを読み込んでそのままPerlとして実行
open INI, "TsMovie2xConverter.ini";
my $SettingCmds = join '', <INI>;
eval $SettingCmds;
warn $@ if $@;
close INI;


my $report = "";

# カレントフォルダのゴミ削除
for my $png (<*.png>) {
	unlink $png;
}
# 出力フォルダのゴミ削除
for my $file (<dst/*.*>) {
	unlink $file;
}

for my $movie ( glob("*".$extension) ) {
	$movie_name = $movie ;
	my ($movie_base) = $movie_name =~ /([^\\]+)\.\w+$/;

	# 該当ムービーのFPSを得る
	my $cmd = `"$ffprobe" -hide_banner $movie_name 2>&1`;
	my ($fps) = $cmd =~ /, ([0-9\.]+) fps,/m;

	# ムービーを連番pngで
	print `"$ffmpeg" -i "$movie_name" -f image2 -vcodec png "$movie_base\_%04d.png"`;

	my $movie_sound = "dst/$movie_base\.wav";
	# ムービーから音をcodecなどもそのままで
	print `"$ffmpeg" -vn -i "$movie_name" -acodec copy "$movie_sound"`;

	
	# 連番の画像を各々２倍の縦横サイズとする。
	for my $png (glob("$movie_base\_*.png")) {
		my $png_2x = $movie_base . "_times";

		# waifu2x で２倍にする。元の名前の_2xというファイル名で出力
		print "$png" . "\n   ⇒";
		print `$waifu2x -i "$png" -m noise_scale --scale_ratio 2 --noise_level 1 --process $process -o "$png_2x.png"`;

		# 元のファイル名へと上書き
		unlink $png;
		rename "$png_2x.png", $png;
	}

	my $movie_base_2x = "dst/$movie_base\_2x" . $extension;

	# ロスレスで作成。
	print `"$ffmpeg" -f image2 -r $fps -i "$movie_base\_%04d.png" -r $fps -an -vcodec libx264 -pix_fmt yuv420p -crf 0 "$movie_base_2x"`;

	# 動画と音声の合体
	print `"$ffmpeg" -i "$movie_base_2x" -i "$movie_sound" -acodec copy -vcodec copy "dst/$movie_base$extension"`;

	# 一時ファイルの削除(音声と映像)
	unlink $movie_base_2x;
	unlink $movie_sound;

	# 一時ファイルの削除(png)
	for my $png (<*.png>) {
		unlink $png;
	}

}