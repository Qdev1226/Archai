// Get the modal
var modal = document.getElementsByClassName("modal");

// Get the button that opens the modal
var btn = document.querySelectorAll(".video-play");

// Get the <span> element that closes the modal
var span = document.querySelectorAll(".close");

var video = document.getElementsByTagName("video");


var player = document.querySelectorAll('iframe.player');

btn.forEach((element, index) => {
  console.log(span);
  var video = element.parentNode.parentNode.querySelector('video');

  if (video) {

    const play = () => {
      if( 0 !== video.length)
        video.play();
    }

    element.onclick = function(e) {
      console.log(e);
      e.preventDefault();
      console.log(e);
      console.log(index);
      modal[index].classList.add('active');
    
      play(video);
    }
  }

  var iframe = element.parentNode.parentNode.querySelector('iframe.player');

  if (iframe) { 
    // console.log(iframe);
    var src = iframe.getAttribute('src');
    iframe.setAttribute('src', '');

    element.onclick = function(e) {
      e.preventDefault();
      console.log(e);
      iframe.setAttribute('src', src);
      modal[index].classList.add('active');
    }

  }

  span[index].onclick = function(e){
    console.log(video);
    modal[index].classList.remove('active');
    if( video ) {
      // Stop video play on close
      video.pause();
      video.currentTime = 0;
    }
    if(iframe){
      // Stop iframe video play on close
      iframe.setAttribute('src', '');
    }
  };
  
});
