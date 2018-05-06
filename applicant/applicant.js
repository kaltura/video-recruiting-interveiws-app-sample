
var mediaRecorder;
var recordedBlobs;
var sourceBuffer;
var entries = []; 
var partnerId; 
var kalturaSession; 
var uiConfId; 
var metadataProfileId;

var fullName = null; 
var email = null; 
var linkedin = null; 

var currentQuestion = 0; 
var playlistId = null; 
var questions; 
var seconds; 

var header = document.querySelector('#header').textContent;
var recorder = document.querySelector('#recorder');
var beginButton = document.querySelector('button#begin');
var doneButton = document.querySelector('button#done');

beginButton.onclick = beginInterview; 
doneButton.onclick = endInterview; 

getConfigurables(); 

function validateName(input) {
  if (/^.*\w.*\s.*\w.*$/.test(input.value) == true) {
    fullName = input.value
    input.style.borderColor = "#2ecc71";
  } else {
    name = null; 
    input.style.borderColor = "#e74c3c";
  }
}

function validateEmail(input) {
  if (/\S+@\S+\.\S+/.test(input.value) == true) {
    email = input.value
    input.style.borderColor = "#2ecc71";
  } else {
    email = null
    input.style.borderColor = "#e74c3c";
  }
}

function validateLinkedin(input) {
  if (input.value.indexOf("linkedin.com/in/") > -1) {
    linkedin = input.value
    input.style.borderColor = "#2ecc71";
  } else {
    linkedin = null
    input.style.borderColor = "#e74c3c";
  }
}

/*
 * When the user clicks start, credentials needed for uploading 
 * Are retrieved using applicant info. 
 * The current question will is displayed 
 * And the user has the option to begin recording 
 */ 

function beginInterview() {

  if (email && fullName && linkedin) {
    getKS(); 
    document.querySelector('#errorMessage').style.display = "none";
    document.querySelector('#form').style.display = "none";
    var recorder = document.querySelector('#recorder'); 

    recorder.style.display = "block"; 
    recordButton.disabled = false;
    document.querySelector('#question').innerHTML = questions[currentQuestion];
    beginButton.style.display = "none"; 

  }
  else {
    document.querySelector('#errorMessage').innerHTML = "Please correct the form";
  }
}

/*
 * Calls kalturaSession.php to get a KS using the details of the applicant 
 */

function getKS() {
  var ksUrl = 'userKS.php?email='+email+'&name=' + fullName + '&linkedin=' + linkedin;
  $.getJSON(ksUrl, null )
  .done(function( response ) {
    kalturaSession = response.ks; 
    if (!response.ks) handleFail();})
    .fail(function( jqxhr, textStatus, error ) {
      var err = textStatus + ", " + error;
      console.log( "Getting KS failed: " + err );
      handleFail(); 
    });
  }


/*
 * Calls to config.php to get all configurable variables 
 */ 

function getConfigurables() {
  var configUrl = '../config.php?email';
  $.getJSON(configUrl, null )
  .done(function( response ) {
    partnerId = response.partnerId; 
    uiConfId = response.uiConfId;
    seconds = response.seconds; 
    questions = response.questions;  })
    .fail(function( jqxhr, textStatus, error ) {
      var err = textStatus + ", " + error;
      console.log( "Getting config failed: " + err );
    });
  }

/*
 * Check whether all entries in the playlist are ready to be displayed 
 * Every ten seconds, a call is made to status.php with all entry Ids
 * If 1 is returned, the playlist will be displayed 
 */ 

function checkEntries() {
  var interval =  setInterval(function () {
    if (entries.length == questions.length) {
      var getStatusUrl = 'status.php?ks='+kalturaSession+'&entries='+entries+'&entriesCount='+entries.length;
      $.getJSON( getStatusUrl, null )
      .done(function( responseData ) {
        if (responseData.status == 1) {
          document.querySelector('#loader').style.display = "none"; 
          showPlaylist(); 
          clearInterval(interval); 
        }})
      .fail(function( jqxhr, textStatus, error ) {
        var err = textStatus + ", " + error;
        console.log( "Checking entries failed: " + err );
      });
    }
  }, 10000);
}

/*
 * Embeds Kaltura Widget with the playlist 
 */ 

function showPlaylist() {
  console.log("playlistId: "+playlistId); 
  document.querySelector('#header').style.display = "none"; 
  kWidget.embed({ 
    "targetId": "kalturaPlayer", 
    "wid": "_"+partnerId, 
    "uiconf_id": uiConfId, 
    "flashvars": { "streamerType": "auto", "playlistAPI.kpl0Id": playlistId, "ks": kalturaSession}}); 
}

function retry() {
  recordedVideo.pause(); 
  $('#recorder').show(); 
  recordButton.style.display = "block"; 
  $('#preview').hide(); 
  recordButton.textContent = 'Start Recording';
}

function nextQuestion() {
  recordedVideo.pause(); 
  recordButton.style.display = "block"; 
  uploadVideo(); 
  document.querySelector('#preview').style.display = "none"; 
  document.querySelector('#recorder').style.display = "block";
  document.querySelector('#question').innerHTML = questions[currentQuestion];
  recordButton.textContent = 'Start Recording';
}

function endInterview() {
  uploadVideo(); 
  document.querySelector('#preview').style.display = "none"; 
  document.querySelector('#question').style.display = "none"; 
  document.querySelector('#header').textContent = "Thank you! Please wait while we prepare your videos";
  document.querySelector('#loader').style.display = "block"; 

  getConfigurables(); 
  checkEntries(); 
}

function JSONApiRequest(endpoint, data, callback) {
  var xhr = new XMLHttpRequest();
  xhr.open("POST", endpoint, true);
  xhr.responseType = "json";
  xhr.onload = function(event) {
    callback(event.target.response);
  };
  xhr.send(data);
}

/*
 * Blob is created with video data 
 * Request is made to Kaltura API for a tokenId 
 * The video is uploaded with js chunked upload widget 
 * Learn more here https://github.com/kaltura/chunked-file-upload-jquery
 */ 

async function uploadVideo() {
  recordedVideo.pause(); 
  currentQuestion++; 

  var blob = new Blob(recordedBlobs, {type: 'video/webm'});
  var endpoint = 'https://www.kaltura.com' + '/api_v3/';
  var fileType = 'video'; // or "audio"
  var entryName = email+'-'+(currentQuestion);
  var fileName = entryName + '.webm'; // or "wav"
  var formData = new FormData();
  var objectType = "1" /* KalturaMetadataObjectType.ENTRY */;

  formData.append(fileType + '-filename', fileName);
  formData.append('uploadToken:objectType', 'KalturaUploadToken');
  formData.append('uploadToken:fileName',encodeURIComponent(fileName));
  formData.append('ks', kalturaSession);
  formData.append('format', 1);
  JSONApiRequest(endpoint+'/service/uploadToken/action/add',formData, 
    function (token) {
      console.log("token: "+token.id); 
      uploadToken=token.id;
      formData = new FormData();
      formData.append('fileData', blob);
      formData.append('uploadTokenId', uploadToken);
      formData.append('resume',false);
      formData.append('resumeAt',false);
      formData.append('finalChunk',true);
      formData.append('ks', kalturaSession);
      formData.append('format', 1);
      JSONApiRequest(endpoint+'/service/uploadToken/action/upload', formData,
        function (response) {   
          addFile(response, fileName, endpoint);
        });
    });
  }

  function addFile(response, fileName, endpoint) {
      JSONApiRequest(endpoint+'/service/media/action/addFromUploadedFile?'+ 
        "ks=" + kalturaSession +
        "&format=1" +
        "&mediaEntry:name="+fileName +
        "&mediaEntry:mediaType=1" +
        "&uploadTokenId="+response.id, null
      ,function (response) {
      if (!response || !response.id) {
        return handleFail(); }
      else {
        entries.push(response.id);
        addMetadata(response.id);
      }
    });
  }

  /*
 * Call metadata.php to add metadata info and to add entry to playlist 
 */ 

  function addMetadata(entryId) {
    var metadata = encodeURIComponent('<?xml version = "1.0" encoding="utf-8" standalone="yes"?><metadata><ApplicantFullName>'+fullName+'</ApplicantFullName><ApplicantEmail>'+email+'</ApplicantEmail><ApplicantLinkedInProfileLink>'+linkedin+'</ApplicantLinkedInProfileLink></metadata>');
    $.getJSON({
    url: 'metadata.php',
    data: {"metadata": metadata, "ks": kalturaSession, "entryId": entryId}, 
    type: 'POST',
    success : function( response ) {
        console.log("metadataId: "+response['profileId']);
        addToPlaylist(entryId); },
    error : function (xhr, ajaxOptions, thrownError){  
        console.log( "Add Metadata Failed: " + xhr.responseText );
      } 
    });
  }

  function addToPlaylist(entryId) {
    var playlistUrl = 'playlist.php' + '?entryId=' + entryId +'&name=' + fullName+ '&email=' + email+ '&ks=' + kalturaSession;
    $.getJSON(playlistUrl, null )
      .done(function(response) {
        console.log("playlistId: " + response.playlistId);
        if (!response.playlistId) 
          document.querySelector('#error').innerHTML = "error adding video to playlist";
        else if (!playlistId) 
          playlistId = response.playlistId; })
      .fail(function( jqxhr, textStatus, error ) {
        var err = textStatus + ", " + error;
        console.log( "Add video to playlist failed: " + err );
      });
  }

  function handleFail() {
    document.querySelector('#error').innerHTML = "there's been an error. please try again";
    recordButton.style.display = "none"; 
    retryButton.style.display = "none"; 
    nextButton.style.display = "none"; 
    doneButton.style.display = "none"; 
  }
