<?php


//ボタンを使い分ける
$toukou_submit_henkou = "toukou_submit";
$hensyu_value_wakeru = "投稿ボタン";

//ファイル関数定義
$filename = "mission_2-6.txt";
//ファイルがない場合、仮に作る
if(!(file_exists($filename))){
    file_put_contents($filename , "");
}


//投稿変数
$name = $_POST["name"];//名前投稿テキストボックス
$comment = $_POST["comment"];//コメント投稿テキストボックス

//削除変数
$sakujyo = $_POST["sakujyo"];//削除番号テキストボックス

//編集変数
$hensyu = $_POST["hensyu"];//編集テキストボックス
$hensyu_no = $_POST["hensyu_no"];//編集番号hidden
$hensyu_go = $_POST["hensyu_go"];//編集後の処理
//###################################################################################################################################################################
//ボタン
$toukou_submit = $_POST["toukou_submit"];//投稿ボタン
$del_submit = $_POST["del_submit"];//削除ボタン
$hensyu_submit = $_POST["hensyu_submit"];//編集ボタン
//パスワード
$password = $_POST["password"];//投稿パスワード
$password_sakujyo = $_POST["password_sakujyo"];//削除パスワード
$password_hensyu = $_POST["password_hensyu"];//編集パスワード
    
//###################################################################################################################################################
//投稿変数
if(!(empty($_POST["name"]))){//名前投稿テキストボックスが空じゃない場合
    $name = $_POST["name"];//名前投稿テキストボックス
    $comment = $_POST["comment"];//コメント投稿テキスト
    $toukou_submit=$_POST["toukou_submit"];//ボタン押された
    //削除変数
    if(isset($_POST["del_submit"])){
        $del_submit=$_POST["del_submit"];//ボタン押された
        $sakujyo = $_POST["sakujyo"];//id取得
    }
    //編集変数
    if(isset($_POST["hensyu_submit"])){
        $hensyu= $_POST["hensyu"];//編集テキストに書き込み
        $hensyu_submit=$_POST["hensyu_submit"];//ボタン押された

        $name_hensyu = $_POST["name_hensyu"];//仮
        $comment_hensyu= $_POST["comment_hensyu"];//仮
    }
    if(!(empty($hensyu_no))){
        $hensyu_no = $_POST["hensyu_no"];//編集のnm仮
        $hensyu_go=$_POST["hensyu_go"];
        $hensyugo_submit=$_POST["hensyugo_submit"];
    }
}
//#####################################################################################################

//####################################################################################################
$toukou_datas = file($filename);//ファイルを配列に（一行ずつ）
    //投稿された投稿番号を配列にいれていく
$index = array();
$toukou_no = array();//同じ名前にする
foreach($toukou_datas as $toukou_data){
    $toukou_waketayo = explode("<>",$toukou_data);
    $toukou_no[] = $toukou_waketayo[0];//投稿番号を配列にいれたすべて
}
//###################################################################################################
 //もしファイルの中身があれば配列の投稿番号の最高値を取得する
 if(file_exists($filename)){
    if(!(empty($toukou_no))){
//        print '$toukou_noの配列の中身はあります。';
        $max = max($toukou_no);
    }else{
        echo "テキストファイルが空です";
    }
 }
//###################################################################################################
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="keiziban2.css">
    <title>Document</title>
</head>
<body>
<?php
$page_flag = "welcome";//初期値
if(isset($toukou_submit)){
    $page_flag = "toukou";
}elseif(isset($del_submit)){
    $page_flag = "sakujyo";
}elseif(isset($hensyu_submit)){
    $page_flag = "hensyu";
}elseif(isset($hensyugo_submit)){
    $page_flag = "hensyu_go";
}
//#######################################################################################################################
switch($page_flag){
case "toukou":
    if($name == "" || $comment == ""){
        echo "何か入力してください。";
    }else{
        $toukou_submit_henko = "toukou_submit";
        $hensyu_value_wakeru = "投稿ボタン";
        $count = $max + 1;
        $kakikomi = $count."<>".$name ."<>".$comment."<>".date("Y/m/d/H:i")."<>".$password."<>";

        $fp = fopen($filename,"a");
        fwrite($fp , $kakikomi."\n");
        fclose($fp);
    }
break;
//####################################################################################################
case "sakujyo":
    if(!($sakujyo == "") && is_numeric($sakujyo)){
        foreach( $toukou_datas as $toukou_data){
            $index = explode("<>" , $toukou_data);
                if($password_sakujyo==$index[4]){
                    if (in_array($sakujyo,$toukou_no)){//削除番号が削除配列の中にあった場合
                        $fp = fopen($filename , "w");//ファイルの中身を削除
                        fwrite($fp,"");
                        fclose($fp);//ファイルを閉じる

                        foreach( $toukou_datas as $toukou_data){
                            $index = explode("<>" , $toukou_data);
                            if($index[0] == $sakujyo){
                                echo "消せたよ！";
                            }else{
                                $fp = fopen($filename , "a");//今空
                                fwrite($fp , $toukou_data);//<>付きで書き込まれてく
                                fclose($fp);
                            }
                        }
                    }else{
                        echo "削除できない番号でした";
                    }
                }
        }
    }elseif($sakujyo == ""){
        echo "入力してください";
    }elseif(!(is_numeric($sakujyo))){
        echo "数字をいれてね";
    }

break;
//######################################################################################################
case "hensyu":
if($hensyu == ""){
        echo "何か入力してください。";
}
        if(!($hensyu == "") && is_numeric($hensyu)){
            foreach( $toukou_datas as $toukou_data){
                $index = explode("<>" , $toukou_data);
                    if($password_hensyu==$index[4]){
                            if (in_array($hensyu,$toukou_no)){//編集番号が配列の中にあった場合
                                foreach( $toukou_datas as $toukou_data){
                                 $index = explode("<>" , $toukou_data);
                                        if($index[0] == $hensyu){//行番号と編集番号が一致した場合
                                            $name_index1 = $index[1];
                                            $name_index2 = $index[2];
                                            $toukou_submit_henkou = "hensyugo_submit";
                                            $hensyu_value_wakeru = "編集ボタン";
                                        }
                                }
                            }else{
                                echo "削除できない番号でした";
                            }
                    }
            }
        }elseif($hensyu == ""){
            echo "入力してください";
        }elseif(!(is_numeric($hensyu))){
            echo "数字をいれてね";
        }
break;

case "hensyu_go":
    if(!($hensyu_no == "")){
        if (in_array($hensyu_no , $toukou_no)){//編集番号が削除配列の中にあった場合
                $fp = fopen($filename , "w");//ファイルの中身を削除
                fwrite($fp , "");
                fclose($fp);//ファイルを閉じる
//                echo $toukou_datas."<br>";

            foreach( $toukou_datas as $toukou_data){
                $index = explode("<>" , $toukou_data);
                if($index[0] == $hensyu_no){
                    $kakikomi_henkou = $hensyu_no."<>".$name."<>".$comment."<>".date("Y/m/d/H:i")."<>".$password."<>"."\n";
                    file_put_contents($filename , $kakikomi_henkou , FILE_APPEND);
                }else{
                    file_put_contents($filename , $toukou_data , FILE_APPEND);
                }
            }
        }
    }elseif($sakujyo == ""){
        echo "入力してください";
    }
        
break;

}
//#####################################################################################################################
?>
<div class="hiroba"><STRONG>LINE風チャット広場</STRONG></div>
<div class= "kakikomihiroba">
    <form  action="mission2-5.php" method="post">
<center>
<div>------------テキスト-----------<br />
    <input type="text" name="name" placeholder="名前" value="<?php echo $name_index1;?>"><br />
    <input type="text" name="comment" placeholder="コメント" value="<?php echo $name_index2;?>"><br />
    <input type="password" name="password" placeholder="パスワード" value=""><br />

    <input type="hidden" name="hensyu_no" placeholder="編集番号" value="<?php echo $hensyu;?>">
    <input name="<?php echo $toukou_submit_henkou;?>" type="submit" value="<?php echo $hensyu_value_wakeru;?>"><br />
<br />-------------------------------------</div>

<div>----------------削除---------------<br />
    <input type="text" name="sakujyo" placeholder="削除対象番号"><br />
    <input type="password" name="password_sakujyo" placeholder="パスワード" value=""><br />
    <input name="del_submit" type="submit" value="削除"><br />
<br />-------------------------------------</div>

<div>----------------編集---------------<br />
    <input type="text" name="hensyu" placeholder="編集対象番号"><br />
    <input type="password" name="password_hensyu" placeholder="パスワード" value=""><br />
    <input name="hensyu_submit" type="submit" value="編集"><br />
<br />-------------------------------------</div>
</center>
</form>
</div>

<!--<div class= "hyouzi">-->
<?php //履歴表示
$toukou_echos = file($filename);
    echo '<div class= "hyouzi_zentai">';
foreach( $toukou_echos as $toukou_echo ){
//    echo "hyuji".$toukou_echo."<br>"
//////////explode関数で更に細かく配列に分けていく/////////////////
    $show_data = explode("<>",$toukou_echo);
   echo '<div class= "hyouzi">';
    echo $show_data[0]."　".$show_data[1]."　".$show_data[2]."　".$show_data[3].
        '</div><br>';
    '</div>';
    }

?>



</body>
</html>