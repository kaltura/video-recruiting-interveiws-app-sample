<?php
require_once('../config.inc');
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="recruiter.css"> 

<title>Recruiter</title>˜
</head>

<body>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnapisec.kaltura.com/p/<?php echo PARTNER_ID; ?>/sp/<?php echo PARTNER_ID; ?>00/embedIframeJs/uiconf_id/<?php echo PLAYER_UICONF_ID; ?>/partner_id/<?php echo PARTNER_ID; ?>"></script>

  <!--Bootstrap-->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<div class="container">
      <h1>Kaltura Recruiting Video Application</h1>
      <h3>Search to view applicant videos</h3>

<div class="form-group" id="login">
<form action="">

    <input id="email" class="form-control" type="email" onblur="validateEmail(this)" placeholder="email" required> <br/>

    <input id="password" class="form-control" type="password" onblur="validatePassword(this)" placeholder="password" required><br/>
    <div id ="errorMessage" class="form-text text-muted"></div> <br/>
</form>

  <button id="submit" type="submit" class="btn btn-primary" onClick="submit();return false;">Submit</button>
</div>
<div id="search">
  <form class="form-inline">
      <label for="staticEmail2" class="sr-only">Email</label>
      <input class="form-control mr-sm-2" type="text" id="searchText" placeholder="Search" onkeyup="search()"  aria-label="Search">
    <button class="btn btn-outline-success my-2 my-sm-0" id="searchButton" type="submit" onClick="search();return false;">Search</button>
  </form>
  <br/>
</div>

<ul id="entries"></ul>  
<div id="lightbox" class="lightbox">
  <span class="close cursor" onclick="closeModal()">&times;</span>

      <div id="kalturaPlayer" style="width: 1000px; height: 330px;"></div>

</div>

<script src="https://webrtc.github.io/adapter/adapter-latest.js"></script>
<script src="recruiter.js"></script>
</body>

</html>
