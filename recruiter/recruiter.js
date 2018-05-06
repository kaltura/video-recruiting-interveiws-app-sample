var searchButton = document.querySelector('#searchButton'); 
var partnerId; 
var uiConfId; 
var kalturaSession; 

var email; 
var password; 

getConfigurables(); 

/*
 * Call search.php with at least three characters 
 * List of entries will be displayed in a list 
 */ 
function search(){
	var text = document.querySelector('#searchText').value; 
	document.querySelector('#lightbox').style.display = "none";

	if (text.length >2) { 
		var searchUrl = 'search.php?term='+text+"&ks="+kalturaSession;
	      $.getJSON( searchUrl, null )
	      .done(function( responseData ) {
	          displayList(responseData);})
	      .fail(function( jqxhr, textStatus, error ) {
	          var err = textStatus + ", " + error;
	          console.log( "Search failed " + err );
	      });
	}
	else if (text.length == 0) { 
		document.querySelector('#entries').innerHTML = "" ; 
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

function validatePassword(input) {
  if (input.value.length > 6) {
    password = input.value
    input.style.borderColor = "#2ecc71";
  } else {
    password = null
    input.style.borderColor = "#e74c3c";
  }
}

function submit(){
	if (email && password)  
		login(); 
  	else 
  		document.querySelector('#errorMessage').innerHTML = "Please provide email address and password";
}

function login() {
    $.getJSON({
    url: 'login.php',
    data: {"email": email, "password": password}, 
    type: 'POST',
    success : function( response ) {
        console.log(response);
		kalturaSession = response.ks;
		showSearch(); },
    error : function (xhr, ajaxOptions, thrownError){  
        console.log( "Login Failed: " + xhr.responseText );
		document.querySelector('#errorMessage').innerHTML = "Wrong credentials";} 
    });
  }

 function showSearch() {
	document.querySelector('#login').style.display = "none";
	document.querySelector('#search').style.display = "block";
 }

/*
 * Get necessary credentials for the Kaltura embed widget 
 */ 
function getConfigurables() {
  var configUrl = '../config.php?';
  $.getJSON(configUrl, null )
  .done(function( response ) {
    partnerId = response.partnerId; 
    uiConfId = response.uiConfId; })
    .fail(function( jqxhr, textStatus, error ) {
      var err = textStatus + ", " + error;
      console.log( "Getting credentials failed: " + err );
    });
  }

 /*
 * Add all search results to ul 
 */  
function displayList(entries){
	var list = document.getElementById("entries"); 
	list.innerHTML = ""; 
	entries.forEach(function(entry) {
  		console.log(entry);
  		list.innerHTML += "<li id='entry' onclick=displayLightbox('"+entry['id']+"')><div id='thumbnail'><img src="+entry['thumbnail']+"></div><div id='details'><div id='name'>"
  		+entry['name']+"</div><div id='email'>"
  		+entry['email']+"</div><div id='linkedin'>"
  		+entry['linkedin']+"</div></div></li>";
	});
}


 /*
 * The embed widget should include the KS 
 * that has permissions to view videos in the category 
 */ 

function displayLightbox(entry) {
	 kWidget.embed({ 
	    "targetId": "kalturaPlayer", 
	    "wid": "_"+partnerId, 
	    "uiconf_id": uiConfId, 
      "flashvars": { "streamerType": "auto", "playlistAPI.kpl0Id": entry, "ks": kalturaSession}}); 

	document.querySelector('#lightbox').style.display = "block";
}

function closeModal() {
  document.querySelector('#lightbox').style.display = "none";
}

