<?php
function replace_face_custom_table($dirpath, $nobu_face_custom_table_tr_str) {

    # ○○軍の中にある△△系というカテゴリ名だけ取り出す
    # フルパス⇒カテゴリ名のみ
    $category_name = explode("/", $dirpath);
  $category_name = end($category_name);


    # カレントのフェイスハッシュ
    $current_face_values = [
       'category_name'=>'',
       '1'=>'',
       '2'=>'',
       '3'=>'',
       '4'=>'',
       '5'=>'',
       '6'=>'',
       '7'=>'',
       '8'=>'',
       '9'=>'',
       '10'=>'',
       '11'=>'',
       '12'=>'',
       '13'=>'',
       '14'=>'',
    ];

    $current_face_values['category_name'] = $category_name;

  $fach_html_hash = array(); // 連想配列初期化

    $col_cnt = 0;
    $col_max = 9;
    $row_cnt = 0;

    foreach( glob($dirpath . '/*.bmp') as $bmp_file_name_path) {

    // 何もしないファイルのパターン
    if ( stristr($bmp_file_name_path, '_org.') ||
             stristr($bmp_file_name_path, '_24bit.') ||
             stristr($bmp_file_name_path, '_anim.') ) {
        continue;
    }

    // 処理するファイルだ。
    $col_cnt++;

        // 24bitの文字列を作成
        $info = pathinfo($bmp_file_name_path);
    $bmp_file_name_24bit_path = $info["dirname"] . "/" . $info["filename"] . '_24bit.' . $info["extension"];

    if ( file_exists($bmp_file_name_24bit_path) ) {
      $current_face_values[(string)$col_cnt] = "<img width='64' height='80' src='" . $bmp_file_name_24bit_path . "'>" .
                           "<img width='64' height='80' src='" . $bmp_file_name_path . "'>";
    } else {
      $current_face_values[(string)$col_cnt] = "<img width='64' height='80' src='" . $bmp_file_name_path . "'>";
    }

    $bmp_file_name_anim_path = $info["dirname"] . "/" . $info["filename"] . '_anim.' . $info["extension"];
    if ( file_exists($bmp_file_name_anim_path) ) {
      $current_face_values[(string)$col_cnt] .= "<br><a class='face_anim' href='" . $bmp_file_name_anim_path . "'><i class='fa fa-video-camera fa-fw'></i>顔アニメ</a> ";
    }
  }

  $replaceKeys = Array( "%(category_name)s",
              "%(1)s",
              "%(2)s",
              "%(3)s",
              "%(4)s",
              "%(5)s",
              "%(6)s",
              "%(7)s",
              "%(8)s",
              "%(9)s",
              "%(10)s",
              "%(11)s",
              "%(12)s",
              "%(13)s",
              "%(14)s" );
  $replaceVals = Array( $current_face_values["category_name"],
              $current_face_values["1"],
              $current_face_values["2"],
              $current_face_values["3"],
              $current_face_values["4"],
              $current_face_values["5"],
              $current_face_values["6"],
              $current_face_values["7"],
              $current_face_values["8"],
              $current_face_values["9"],
              $current_face_values["10"],
              $current_face_values["11"],
              $current_face_values["12"],
              $current_face_values["13"],
              $current_face_values["14"] );

  $result_replaced_html = str_replace($replaceKeys, $replaceVals, $nobu_face_custom_table_tr_str);

  return $result_replaced_html;

}


function get_nobu_face_custom_table($gung, $target_dir) {

    $nobu_face_custom_table_tr_str = file_get_contents("nobu_face_custom_table_tr.html");

    $result_html = "";

    foreach( glob('cnt_img/' . $target_dir . '/' . $gung . '/*系' ) as $dirname ) {
    $result_html .= replace_face_custom_table($dirname, $nobu_face_custom_table_tr_str);
  }

  return $result_html;
}


function get_nobu_face_custom() {
    $nobu_face_custom_str = file_get_contents("nobu_face_custom.html");
  $result_html = get_nobu_face_custom_table($_GET['gung'], $_GET['tdir']);
  $result_html = str_replace("%(nobu_face_custom_table)s", $result_html, $nobu_face_custom_str);
  return $result_html;
}

?>