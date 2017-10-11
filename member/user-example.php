<script>
function backToParent(){
      window.opener.document.getElementById('data').value = "This";
}
</script>
<?php
/*
 * Copyright 2011 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
include_once "google-api-php-client-master/examples/templates/base.php";
session_start();

require_once "google-api-php-client-master/autoload.php";

/************************************************
  ATTENTION: Fill in these values! Make sure
  the redirect URI is to this page, e.g:
  http://localhost:8080/user-example.php
 ************************************************/
 $client_id = '391047081213-al473jjos2t3f9up4cpf2t9kqo6n99qs.apps.googleusercontent.com';
 $client_secret = 'VBiBO0M6hGrqw41vfZYlFd9G';
 $redirect_uri = 'http://localhost/google-api-php-client-master/examples/user-example.php';

/************************************************
  Make an API request on behalf of a user. In
  this case we need to have a valid OAuth 2.0
  token for the user, so we need to send them
  through a login flow. To do this we need some
  information from our API console project.
 ************************************************/
$client = new Google_Client();
$client->setClientId($client_id);
$client->setClientSecret($client_secret);
$client->setRedirectUri($redirect_uri);
$client->addScope(array(
"https://www.googleapis.com/auth/userinfo.email",
"https://www.googleapis.com/auth/userinfo.profile"
));

/************************************************
  When we create the service here, we pass the
  client to it. The client then queries the service
  for the required scopes, and uses that when
  generating the authentication URL later.
 ************************************************/
$service = new Google_Service_Oauth2($client);

/************************************************
  If we're logging out we just need to clear our
  local access token in this case
 ************************************************/
if (isset($_REQUEST['logout'])) {
  unset($_SESSION['access_token']);
}

/************************************************
  If we have a code back from the OAuth 2.0 flow,
  we need to exchange that with the authenticate()
  function. We store the resultant access token
  bundle in the session, and redirect to ourself.
 ************************************************/
if (isset($_GET['code'])) {
  $client->authenticate($_GET['code']);
  $_SESSION['access_token'] = $client->getAccessToken();
  $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
  header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
}

/************************************************
  If we have an access token, we can make
  requests, else we generate an authentication URL.
 ************************************************/
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
  $client->setAccessToken($_SESSION['access_token']);
} else {
  $authUrl = $client->createAuthUrl();
}

if ($client->getAccessToken()) {
  $userinfo = $service->userinfo->get();
  $_SESSION['access_token'] = $client->getAccessToken();
  
  foreach($userinfo as $key=>$value){
  	echo $key. " ". $value. "<br/>";
  }
  
  echo "<script>backToParent();</script>";
}else{
  echo "<script>window.close();</script>";
}

echo pageHeader("User Query - URL Shortener");
if (
    $client_id == '<YOUR_CLIENT_ID>'
    || $client_secret == '<YOUR_CLIENT_SECRET>'
    || $redirect_uri == '<YOUR_REDIRECT_URI>') {
  echo missingClientSecretsWarning();
}
?>

<script type='text/javascript'>
function openWindow(){
      var w_width=500;
      var w_height=550;
      var x=(screen.width-w_width)/2;
      var y=(screen.height-w_height)/2;
      var newwin='width='+w_width+',height='+w_height+',top='+y+',left='+x;
      window.open('<?php echo $authUrl; ?>','_blank',newwin);
}
</script>

<div class="box">
  <div class="request">
    <?php if (isset($authUrl)): ?>
      <input type="button" value="Connect Me!" onclick="javascript:openWindow()"/>
      <input id="data" name="data" class="data" type="text">
      <a class='login' href='<?php echo $authUrl; ?>'>Connect Me!</a>
    <?php else: ?>
      <form id="url" method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <input name="url" class="url" type="text">
        <input type="submit" value="Shorten">
      </form>
      <a class='logout' href='?logout'>Logout</a>
    <?php endif ?>
  </div>

  <?php if (isset($short)): ?>
    <div class="shortened">
      <?php var_dump($short); ?>
    </div>
  <?php endif ?>
</div>
<?php
echo pageFooter(__FILE__);
