<?php
require_once('../config.inc');
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="applicant.css"> 

<title>Recruitment Application</title>
</head>

<body>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnapisec.kaltura.com/p/<?php echo PARTNER_ID; ?>/sp/<?php echo PARTNER_ID; ?>00/embedIframeJs/uiconf_id/<?php echo PLAYER_UICONF_ID; ?>/partner_id/<?php echo PARTNER_ID; ?>"></script>

  <!--Bootstrap-->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<div class="container">
      <h1>Kaltura Recruiting Video Application</h1>
      <h3><div id="header">Using this application you will be asked a couple questions about yourself and your background. You are expected to answer in <?php echo SECONDS; ?> second video segments.</div></h3>

<div class="form-group" id="form">
<form action="">

    <label for="name">Full name:</label>
    <input id="name" class="form-control" type="text" onblur="validateName(this)" placeholder="full name" required> <br/>

    <label for="email">email:</label>
    <input id="email" class="form-control" type="email" onblur="validateEmail(this)" placeholder="email" required><br/>

    <label for="linkedin">linkedin:</label>
    <input id="linkedin" class="form-control" onblur="validateLinkedin(this)" placeholder="linkedin" required>
    <div id ="errorMessage" class="form-text text-muted"></div>
</form>

  <button id="begin" class="btn btn-primary">Begin Interview</button>
</div>

<div id="question"></div>

<div id="recorder">
  <video id="gum" autoplay muted></video>
  <br/><button class="btn btn-danger" id="record">Start Recording</button>
  <div id="timer"></div>
</div>

<div id="preview">
  <video id="recorded" autoplay></video>
  <div>
    <button class="btn btn-secondary" id="retry">Try Again</button>
    <button class="btn btn-success" id="next">Next Question</button>
    <button class="btn btn-success" id="done">I'm Done</button>
  </div>
</div>

<div id="error"></div>

<div id="loader"></div>
<div id="kalturaPlayer" style="width: 740px; height: 330px;"></div>
</div>

<script src="https://webrtc.github.io/adapter/adapter-latest.js"></script>
<script src="applicant.js"></script>
<script src="recorder.js"></script>

</body>

</html>
