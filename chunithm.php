<?php
/*
  +---------------------------------------------------+
  | CHUNITHM Rate Calculator           [chunithm.php] |
  +---------------------------------------------------+
  | Copyright (c) 2015-2016 Akashi_SN                 |
  +---------------------------------------------------+
  | This software is released under the MIT License.  |
  | http://opensource.org/licenses/mit-license.php    |
  +---------------------------------------------------+
  | Author: Akashi_SN <info@akashisn.info>            |
  +---------------------------------------------------+
*/

//-----------------------------------------------------
// header
//-----------------------------------------------------

require("common.php");
session_start();

//userが指定されていない
if(!isset($_GET['user'])){
  //cookieがPOSTされている
  if(isset($_POST['userid'])){
    //UserIdが見つかった
    if(userid_get($_POST['userid'])){
      $userid = userid_get($_POST['userid']);
      $_SESSION['userid'] = $userid;
      header("HTTP/1.1 301 Moved Permanently");
      header("Location: /chunithm/main.php");
    }
    //UserIdが見つからない
    else{
      setcookie("errorCode",100001);
      header("HTTP/1.1 301 Moved Permanently");
      header("Location: /chunithm/error.html");
      exit();
    }
  }
  //cookieがPOSTされていない
  else{
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: /");
    exit();
  }
}
//user(hash)が指定されてる
else{
  $json = UserData_show($_GET['user']);
  if($json === null){      
    http_response_code(403);
    exit();
  }
  $json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
  $UserData = json_decode($json,true);
  $UserRateDisp = UserRateDisp($UserData);
  $BestRateDisp = BestRateDisp($UserData);  
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja" dir="ltr">

<head>

  <meta charset="UTF-8" />
  <meta name="format-detection" content="telephone=no">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="Pragma" CONTENT="no-cache">
  <meta http-equiv="Cache-Control" CONTENT="no-cache">
  <meta http-equiv="Expires" CONTENT="-1">
  <meta name="robots" content="INDEX, FOLLOW" />
  <meta property="og:title" content="CHUNITHM Rate Calculator" />
  <meta property="og:type" content="tools" />
  <meta property="og:description" content="チュウニズムのレートを計算するものです。" />
  <meta property="og:url" content="https://akashisn.info/?page_id=52" />
  <meta property="og:image" content="/common/images/chunithm/img.jpg" />
  <meta property="og:site_name" content="Akashi_SNの日記" />
  <link rel="stylesheet" href="https://chunithm-net.com/mobile/common/css/common.css" />
  <link rel="stylesheet" href="https://chunithm-net.com/mobile/common/css/contents.css" />
  <link rel="stylesheet" href="/chunithm/lib/chunithm.css?var=3.6.3" />
  <title>CHUNITHM Rate Calculator</title>
</head>

<body style="width:100%">
  <div id="sub_title">CHUNITHM Rate Calculator</div>
<?php
  echo $UserRateDisp;
  button_show();
  if(isset($_GET['frame'])){
    if($_GET['frame'] === 'Best'){
      if(isset($_GET['sort'])){
        if($_GET['sort'] ===  'Sort_Score'){
          sort_button_show();
          $Sort_Score = Sort_Score($UserData);
          echo $Sort_Score;
        }
        else if($_GET['sort'] ===  'Sort_Rate'){
          sort_button_show();
          echo $BestRateDisp;
        }
        else if($_GET['sort'] ===  'Sort_Diff'){
          sort_button_show();
          $Sort_Diff = Sort_Diff($UserData);
          echo $Sort_Diff;
        }
      }
      sort_button_show();
      echo $BestRateDisp;
    }
    else if($_GET['frame'] === 'Recent'){
      $RecentRateDisp = RecentRateDisp($UserData);
      echo $RecentRateDisp;
    }
    else if($_GET['frame'] === 'Graph'){
      $Graph = Graph($UserData);
      echo $Graph;
    }
    else if($_GET['frame'] === 'Tools'){
      if(isset($_GET['score']) && isset($_GET['baserate'])){
        $tmp['score'] = $_GET['score'];
        $tmp['baserate'] = $_GET['baserate'];
        $tmp['bestrate'] = $UserData['User']["BestRate"];
        $Tools = Tools($tmp);
        echo $Tools;
      }else{
	      $Tools = Tools($tmp);
	      echo $Tools;
	    }
    }
  }
  else{
    sort_button_show();
    echo $BestRateDisp;
  }
?>
  <div style="font-size:15px">CHUNITHM Rate Calculator by Akashi_SN <a href="https://twitter.com/Akashi_SN" class="twitter-follow-button" data-show-count="false">Follow @Akashi_SN</a></div>
</body>
  <script type="text/javascript">
  //Google-Analytics
  (function (d, e, j, h, f, c, b) {
    d.GoogleAnalyticsObject = f;
    d[f] = d[f] || function () {
      (d[f].q = d[f].q || [])
      .push(arguments)
    }, d[f].l = 1 * new Date();
    c = e.createElement(j), b = e.getElementsByTagName(j)[0];
    c.async = 1;
    c.src = h;
    b.parentNode.insertBefore(c, b)
  })(window, document, "script", "https://www.google-analytics.com/analytics.js", "ga");
  ga("create", "UA-86195263-2", "auto");
  ga("send", "pageview");
  //twitter
  ! function (j, h, i) {
    var k, d = j.getElementsByTagName(h)[0]
      , l = /^http:/.test(j.location) ? "http" : "https";
    if (!j.getElementById(i)) {
      k = j.createElement(h);
      k.id = i;
      k.async = true;
      k.src = l + "://platform.twitter.com/widgets.js";
      d.parentNode.insertBefore(k, d)
    }
  }(document, "script", "twitter-wjs");
  </script>
</html>