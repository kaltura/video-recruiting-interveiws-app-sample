var gumVideo = document.querySelector('video#gum');
var recordedVideo = document.querySelector('video#recorded');
var recordButton = document.querySelector('#record');
var retryButton = document.querySelector('button#retry');
var nextButton = document.querySelector('button#next');
var displayTimer = document.querySelector('#timer');

recordButton.onclick = startRecording;
retryButton.onclick = retry;
nextButton.onclick = nextQuestion;

var mediaSource = new MediaSource();
mediaSource.addEventListener('sourceopen', handleSourceOpen, false);

var constraints = {
  audio: true,
  video: true
};

navigator.mediaDevices.getUserMedia(constraints).
    then(handleSuccess).catch(handleError);

function handleSuccess(stream) {
  recordButton.disabled = false;
  console.log('getUserMedia() got stream: ', stream);
  window.stream = stream;
  if (window.URL) {
    gumVideo.src = window.URL.createObjectURL(stream);
  } else {
    gumVideo.src = stream;
  }
}

function handleError(error) {
  console.log('navigator.getUserMedia error: ', error);
}

function handleSourceOpen(event) {
  console.log('MediaSource opened');
  sourceBuffer = mediaSource.addSourceBuffer('video/webm; codecs="vp8"');
  console.log('Source buffer: ', sourceBuffer);
}

recordedVideo.addEventListener('error', function(ev) {
  console.error('MediaRecording.recordedMedia.error()');
  alert('Your browser can not play\n\n' + recordedVideo.src
    + '\n\n media clip. event: ' + JSON.stringify(ev));
}, true);

function handleDataAvailable(event) {
  if (event.data && event.data.size > 0) {
    recordedBlobs.push(event.data);
  }
}

function startTimer(duration, display) {
  displayTimer.style.display = "block";
    var timer = duration, minutes, seconds;
    var interval =  setInterval(function () {
        minutes = parseInt(timer / 60, 10)
        seconds = parseInt(timer % 60, 10);

        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;
        display.textContent = minutes + ":" + seconds; 

        if (--timer < 0) {
            clearInterval(interval); 
            stopRecording();

        }
    }, 1000);
}

function startRecording() {
  recordButton.style.display = "none"; 
  recordedBlobs = [];
  var options = {mimeType: 'video/webm;codecs=vp9'};
  if (!MediaRecorder.isTypeSupported(options.mimeType)) {
    console.log(options.mimeType + ' is not Supported');
    options = {mimeType: 'video/webm;codecs=vp8'};
    if (!MediaRecorder.isTypeSupported(options.mimeType)) {
      console.log(options.mimeType + ' is not Supported');
      options = {mimeType: 'video/webm'};
      if (!MediaRecorder.isTypeSupported(options.mimeType)) {
        console.log(options.mimeType + ' is not Supported');
        options = {mimeType: ''};
      }
    }
  }
  try {
    mediaRecorder = new MediaRecorder(window.stream, options);
  } catch (e) {
    console.error('Exception while creating MediaRecorder: ' + e);
    alert('Exception while creating MediaRecorder: '
      + e + '. mimeType: ' + options.mimeType);
    return;
  }
  console.log('Created MediaRecorder', mediaRecorder, 'with options', options);
  recordButton.textContent = 'Stop Recording';
  retryButton.disabled = true;
  mediaRecorder.ondataavailable = handleDataAvailable;
  mediaRecorder.start(10); // collect 10ms of data
  console.log('MediaRecorder started', mediaRecorder);

  startTimer(seconds, displayTimer); 
}

function stopRecording() {
  mediaRecorder.stop();
  console.log('MediaRecorder stopped', mediaRecorder);
  var preview = document.querySelector('#preview'); 
  document.querySelector('#recorder').style.display = "none";
  retryButton.disabled = false;
  preview.style.display = "block"; 
  if (currentQuestion+1 == questions.length) {
    end();
  } 
  playRecorded(); 
  recordedVideo.controls = true;
  document.querySelector('#timer').style.display = "none";
}

function playRecorded() {
  var superBuffer = new Blob(recordedBlobs, {type: 'video/webm'});
  recordedVideo.src = window.URL.createObjectURL(superBuffer);
}

function end() {
  nextButton.style.display = "none"; 
  doneButton.style.display = "block";  
}
