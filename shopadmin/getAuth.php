<?php
include_once "Check_Admin.php";

require_once './PHPGangsta/GoogleAuthenticator.php';

$ga = new PHPGangsta_GoogleAuthenticator();

$secret = $ga->createSecret();
?><head>
<LINK href="../css/css.css" type=text/css rel=stylesheet>
</head>
<body style="padding:20px;"
<div>
  <h2>
  設定 Google Authenticator
  </h2>
  <div>
  安裝 Google Authenticator 應用程式。
  </div>
  <ol>
  <li>
  透過您的手機前往 Google Play 商店 或 App Store 商店。
  </li>
  <li>
  搜尋 <b>Google Authenticator</b>。 <br>
  <span>(<a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2" target="_blank">從 Google Play 商店下載</a> 或 <a href="http://itunes.apple.com/us/app/google-authenticator/id388497605?mt=8" target="_blank">從 App Store 下載</a>)</span>
  </li>
  <li>
  下載並安裝應用程式。
  </li>
  </ol>
  <div>
  現在，請開啟 Google Authenticator 進行設定。
  </div>
  <div>
  <ol>
  <li>
  在 Google Authenticator 中，輕觸 [選單] 並選取 [設定帳戶] 或 輕觸 +。
  </li>
  <li>
  選取 [輸入提供的金鑰]。
  </li>
  <li>
  在 [輸入帳戶名稱] 中，填入網站名稱。
  </li>
  <li>
  在 [輸入金鑰] 中，輸入您的秘密金鑰：
  <div>
  <div style="width:180px;text-align:center;margin:10px;background-color:#F9EDBE;padding:10px;"><?php echo $secret; ?></div>
  </div>
  </li>
  <li>
  金鑰類型：確實選取 [根據時間]。
  </li>
  <li>
  輕觸 [新增]。
  </li>
  </ol>
  </div>
</div>
<input onclick="setAuth();" value="使用" type="button" style="width:100px">
<input onclick="window.close();" value="不使用" type="button" style="width:100px">
<script language="javascript">
  function setAuth() {
    window.opener.document.getElementById('secretkey').value="<?php echo $secret; ?>";
    window.close();
  }
</script>
</body>