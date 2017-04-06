<?php
require( "/virtual/usr/public_html/xn--rssu31gj1g.jp/index_LIB_contents.php" );

$urlParamPage = $_GET['page'];
$orgParamPage = $_GET['page'];

// デフォルトのページ
if ( $urlParamPage == "" ) {
    $urlParamPage = "nobu_tool_hm_ecmascript";
}


// コンテンツのページテンプレート読み込み
$strPageTemplate = file_get_contents('/virtual/usr/public_html/xn--rssu31gj1g.jp/' . $content_hash[$urlParamPage]['html']);

// まずBOMの除去
$strPageTemplate = preg_replace('/^\xEF\xBB\xBF/', '', $strPageTemplate);



// eclipseのオートフォーマッタが変な所で改行するので対応
$strPageTemplate = preg_replace('/<img\s+src/ms', '<img src', $strPageTemplate);

// widthやheightが無いイメージタグにマッチしたら、widthやheightを入れる。
$strPageTemplate = preg_replace_callback("/(<img src=[\"'])([^\"']+?)([\"'])((\s+attr=[\"']noedge[\"'])?(\s+align=[\"'][a-z]+[\"'])?(\s+attr=[\"']noedge[\"'])?>)/",
                                   function ($matches) {
                                       // httpが含まれている。
                                       if (strpos($matches[0],'http') !== false) {
                                           return $matches[0];

                                       // サイト内の画像
                                       } else {
                                           $strTargetImageFileName = "/virtual/usr/public_html/xn--rssu31gj1g.jp/" . $matches[2];
                                           $timeTargetImageUpdate = filemtime($strTargetImageFileName);
                                           $strTargetImageUpdate = date("YmdHis", $timeTargetImageUpdate);
                                           list($width, $height, $type, $attr) = getimagesize($strTargetImageFileName);
                                           return $matches[1] . $matches[2] . "?v=". $strTargetImageUpdate . $matches[3]  . " " . $attr . $matches[4];
                                       }
                                   }, $strPageTemplate);

$strShCoreHeader = "";
$strShCoreFooter = "";

// brush:***を埋め込んでいても、hilightさせていない所があるので、あえてhilightで判定する。
if ( strpos($strPageTemplate, "(hilight)s") != false ) {
    $strShCoreHeader = "document.write(\"<link rel='stylesheet' type='text/css' href='./hilight/styles/shcore-3.0.83.min.css?v=%(shcorecssupdate)s'>\");";

    // shcoreのスタイルシート
    $timeShcoreCSSUpdate = filemtime("./hilight/styles/shcore-3.0.83.min.css");
    $strShcoreCSSUpdate = date("YmdHis", $timeShcoreCSSUpdate);

    $timeShcoreJSUpdate = filemtime("./hilight/scripts/shcore-3.0.84.min.js");
    $strShcoreJSUpdate = date("YmdHis", $timeShcoreJSUpdate);
    $strShCoreFooter1 = "document.write(\"<script type='text/javascript' src='./hilight/scripts/shcore-3.0.84.min.js?v=%(shcorecssupdate)s'><\/script>\");";
	$strShCoreFooter1 = str_replace("%(shcorecssupdate)s", $strShcoreJSUpdate, $strShCoreFooter1);
    $strShCoreFooter2 = "SyntaxHighlighter.defaults['toolbar'] = false;\n" .
	                    "SyntaxHighlighter.defaults['auto-links'] = false;\n" .
                        "SyntaxHighlighter.all();";
}


// "%(hilight)s"があれば置き換え
// ソースハイライト用。"%(hilight)s"がある時だけ埋め込まれる
$strHilightJS = "";
$strPageTemplate = str_replace("%(hilight)s", $strHilightJS, $strPageTemplate);

// シンタックスハイライター


// ファイルのアーカイブがあれば、更新日時へと置き換え
$fileArchieve = $filetime_hash[$urlParamPage];
if ($fileArchieve) {
    $filetime = filemtime($fileArchieve);
    $fileKeys   = Array( "%(file)s", "%(year)04d", "%(mon)02d", "%(mday)02d" );
    $fileValues = Array($fileArchieve, date("Y", $filetime), date("m", $filetime), date("d", $filetime) );

    $strPageTemplate = str_replace($fileKeys, $fileValues, $strPageTemplate);
}


// css系を置き換え
$strStyleTemplate = file_get_contents("style_dynamic.css");

$normalizeUrlParamPage = $urlParamPage; // 普通のページならそのまま?page=○○○ の部分へと置き換える

// 今表示しているページへの太字等
$strStyleTemplate = str_replace("%(menu_style_dynamic)s", $normalizeUrlParamPage, $strStyleTemplate);

// トップページのテンプレートの読み込み
$strIndexTemplate = file_get_contents("index_HM.html");

// 左のメニューの部分。すでに開いているページに基いて、階層を開くところを決める。
// javascriptの一部を書き出す感じ
$strMenuExpand = "";
if ($orgParamPage != "" and $content_hash[$urlParamPage]['dir'] != "#") {
    $strMenuExpand = "$( '#menu' ).multilevelpushmenu( 'expand', '" . $content_hash[$urlParamPage]['dir'] . "' )";
}

// メインのスタイルシート
$timeStyleUpdate = filemtime("./style.min.css");
$strStyleUpdate = date("YmdHis", $timeStyleUpdate);

// 独自のFontAwesome
$timeFontPluginUpdate = filemtime("./font-awesome/css/font-awesome-plugin.css");
$strFontPluginUpdate = date("YmdHis", $timeFontPluginUpdate);

// メニューのカスタムCSS
$timeMLPMCSSUpdate = filemtime("./jquery/jquery.multilevelpushmenu.min.css");
$strMLPMCSSUpdate = date("YmdHis", $timeMLPMCSSUpdate);

// メニューのカスタムJS
$timeMLPMCustomUpdate = filemtime("./jquery/jquery.multilevelpushmenu.custom.min.js");
$strMLPMCustomUpdate = date("YmdHis", $timeMLPMCustomUpdate);

// index内にある、スタイル、コンテンツ、階層の開きをそれぞれ、具体的な文字列へと置き換える
$array_style    = array( "%(style_dynamic)s", "%(expand)s", "%(styleupdate)s", "%(fontpluginupdate)s", "%(mlpmcssdate)s", "%(mlpmcustomdate)s", "%(shcore_head)s", "%(shcore_foot1)s", "%(shcore_foot2)s", "%(shcorecssupdate)s", "%(content_dynamic)s" );
$array_template = array( $strStyleTemplate, $strMenuExpand, $strStyleUpdate, $strFontPluginUpdate, $strMLPMCSSUpdate, $strMLPMCustomUpdate, $strShCoreHeader, $strShCoreFooter1, $strShCoreFooter2, $strShcoreCSSUpdate, $strPageTemplate );
$strIndexEvaluated = str_replace($array_style, $array_template, $strIndexTemplate);

// トップページとして表示
echo($strIndexEvaluated);
?>

