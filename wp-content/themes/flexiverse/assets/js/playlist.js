/*
Example: https://setstori.blogspot.com/2016/01/nightmareside.html
*/
function simplePlayerMultiInit() {
	setTimeout(() => {
		document.querySelectorAll('.simple-audio-player').forEach((value) => {
			const selector = value.getAttribute('id');

			simpPlaylist('#' + selector);
		});
	}, 500);

}

window.addEventListener("load", function() {
	simplePlayerMultiInit();
});

const getBlockList         = () => wp.data.select('core/block-editor').getBlocks();
let blockList              = getBlockList();
wp.data.subscribe(() => {
	const newBlockList     = getBlockList();
	const blockListChanged = newBlockList !== blockList;
	blockList              = newBlockList;
	if (blockListChanged) {
		simplePlayerMultiInit();
	}
});

// Multiple events to a listener
function addEventListener_multi(element, eventNames, handler) {
	var events = eventNames.split(' ');
	events.forEach(e => element.addEventListener(e, handler, false));
}

// Random numbers in a specific range
function getRandom(min, max) {
	min = Math.ceil(min);
	max = Math.floor(max);
	return Math.floor(Math.random() * (max - min + 1)) + min;
}

// Position element inside element
function getRelativePos(elm) {
	var pPos = elm.parentNode.getBoundingClientRect(); // parent pos
	var cPos = elm.getBoundingClientRect(); // target pos
	var pos  = {};

	pos.top        = cPos.top - pPos.top + elm.parentNode.scrollTop,
		pos.right  = cPos.right - pPos.right,
		pos.bottom = cPos.bottom - pPos.bottom,
		pos.left   = cPos.left - pPos.left;

	return pos;
}

function formatTime(val) {
	var h = 0,
		m = 0,
		s;
	val   = parseInt(val, 10);
	if (val > 60 * 60) {
		h    = parseInt(val / (60 * 60), 10);
		val -= h * 60 * 60;
	}
	if (val > 60) {
		m    = parseInt(val / 60, 10);
		val -= m * 60;
	}
	s    = val;
	val  = (h > 0) ? h + ':' : '';
	val += (m > 0) ? ((m < 10 && h > 0) ? '0' : '') + m + ':' : '0:';
	val += ((s < 10) ? '0' : '') + s;
	return val;
}

function simpPlaylist(selector) {
	function simp_initTime() {
		simp_controls.querySelector('.start-time').innerHTML = formatTime(simp_audio.currentTime); //calculate current value time

		if (!simp_isStream) {
			simp_controls.querySelector('.end-time').innerHTML = formatTime(simp_audio.duration); //calculate total value time
			simp_progress.value                                = simp_audio.currentTime / simp_audio.duration * 100; //progress bar
		}

		// ended of the audio
		if (simp_audio.currentTime == simp_audio.duration) {
			simp_controls.querySelector('.simp-plause .pause-button').style.display = "none";
			simp_controls.querySelector('.simp-plause .play-button').style.display  = "block";
			simp_controls.closest( '.simple-audio-player' ).classList.add('playing');
			simp_audio.removeEventListener('timeupdate', simp_initTime);

			if (simp_isNext) { //auto load next audio
				var elem;
				simp_a_index++;
				if (simp_a_index == simp_a_url.length) { //repeat all audio
					simp_a_index = 0;
					elem         = simp_a_url[0];
				} else {
					elem = simp_a_url[simp_a_index];
				}
				simp_changeAudio(elem);
				simp_setAlbum(simp_a_index);
			} else {
				simp_isPlaying = false;
			}
		}
	}

	function simp_initAudio() {
		// if readyState more than 2, audio file has loaded
		simp_isLoaded                                        = simp_audio.readyState == 4 ? true : false;
		simp_isStream                                        = simp_audio.duration == 'Infinity' ? true : false;
		simp_controls.querySelector('.simp-plause').disabled = false;
		simp_progress.disabled                               = simp_isStream ? true : false;
		if (!simp_isStream) {
			simp_progress.parentNode.classList.remove('simp-load', 'simp-loading');
			simp_controls.querySelector('.end-time').innerHTML = formatTime(simp_audio.duration);
		}

		simp_audio.addEventListener('timeupdate', simp_initTime); //tracking load progress
		if (simp_isLoaded && simp_isPlaying) {
simp_audio.play();
		}

		// progress bar click event
		addEventListener_multi(simp_progress, 'touchstart mousedown', function(e) {
			if (simp_isStream) {
				e.stopPropagation();
				return false;
			}
			if (simp_audio.readyState == 4) {
				simp_audio.removeEventListener('timeupdate', simp_initTime);
				simp_audio.pause();
			}
		});

		addEventListener_multi(simp_progress, 'touchend mouseup', function(e) {
			if (simp_isStream) {
				e.stopPropagation();
				return false;
			}
			if (simp_audio.readyState == 4) {
				simp_audio.currentTime = simp_progress.value * simp_audio.duration / 100;
				simp_audio.addEventListener('timeupdate', simp_initTime);
				if (simp_isPlaying) {
simp_audio.play();
				}
			}
		});
	}

	function simp_loadAudio(elem) {
		simp_progress.parentNode.classList.add('simp-loading');
		simp_controls.querySelector('.simp-plause').disabled = true;
		simp_audio.querySelector('source').src               = elem.dataset.src;
		simp_audio.load();

		simp_audio.volume = parseFloat(simp_v_num / 100); //based on valume input value
		simp_audio.addEventListener('canplaythrough', simp_initAudio); //play audio without stop for buffering

		// if audio fails to load, only IE/Edge 9.0 or above
		simp_audio.addEventListener('error', function() {
			alert('Please reload the page.');
		});
	}

	function simp_setAlbum(index) {
		simp_cover.innerHTML  = simp_a_url[index].dataset.cover ? '<div style="background:url(' + simp_a_url[index].dataset.cover + ') no-repeat;background-size:cover;width:80px;height:80px;"></div>' : '<i class="fa fa-music fa-5x"></i>';
		simp_title.innerHTML  = simp_source[index].querySelector('.simp-source').innerHTML;
		simp_artist.innerHTML = simp_source[index].querySelector('.simp-desc') ? simp_source[index].querySelector('.simp-desc').innerHTML : '';
	}

	function simp_changeAudio(elem) {
		simp_isLoaded                                        = false;
		simp_controls.querySelector('.simp-prev').disabled   = simp_a_index == 0 ? true : false;
		simp_controls.querySelector('.simp-plause').disabled = simp_auto_load ? true : false;
		simp_controls.querySelector('.simp-next').disabled   = simp_a_index == simp_a_url.length - 1 ? true : false;
		simp_progress.parentNode.classList.add('simp-load');
		simp_progress.disabled                               = true;
		simp_progress.value                                  = 0;
		simp_controls.querySelector('.start-time').innerHTML = '00:00';
		simp_controls.querySelector('.end-time').innerHTML   = '00:00';
		elem = simp_isRandom && simp_isNext ? simp_a_url[getRandom(0, simp_a_url.length - 1)] : elem;

		// playlist, audio is running
		for (var i = 0; i < simp_a_url.length; i++) {
			simp_a_url[i].parentNode.classList.remove('simp-active');
			if (simp_a_url[i] == elem) {
				simp_a_index = i;
				simp_a_url[i].parentNode.classList.add('simp-active');
			}
		}

		// scrolling to element inside element
		var simp_active                                = getRelativePos(simp_source[simp_a_index]);
		simp_source[simp_a_index].parentNode.scrollTop = simp_active.top;

		if (simp_auto_load || simp_isPlaying) {
simp_loadAudio(elem);
		}

		if (simp_isPlaying) {
			simp_controls.querySelector('.simp-plause .play-button').style.display  = "none";
			simp_controls.querySelector('.simp-plause .pause-button').style.display = "block";
			//simp_controls.closest( '.simple-audio-player' ).classList.remove('playing');
		}
	}

	function simp_startScript() {
		ap_simp        = document.querySelector(selector);
		simp_audio     = ap_simp.querySelector('.audio');
		simp_album     = ap_simp.querySelector('.simp-album');
		simp_cover     = simp_album.querySelector('.simp-cover');
		simp_title     = simp_album.querySelector('.simp-title');
		simp_artist    = simp_album.querySelector('.simp-artist');
		simp_controls  = ap_simp.querySelector('.simp-controls');
		simp_progress  = simp_controls.querySelector('.simp-progress');
		simp_volume    = simp_controls.querySelector('.simp-volume');
		simp_v_slider  = simp_volume.querySelector('.simp-v-slider');
		simp_v_num     = simp_v_slider.value; //default volume
		simp_others    = simp_controls.querySelector('.simp-others');
		simp_auto_load = simp_config.auto_load; //auto load audio file

		if (simp_config.shide_top) {
simp_album.parentNode.classList.toggle('simp-hide');
		}
		if (simp_config.shide_btm) {
			simp_playlist.classList.add('simp-display');
			simp_playlist.classList.toggle('simp-hide');
		}

		if (simp_a_url.length <= 1) {
			simp_controls.querySelector('.simp-prev').style.display = 'none';
			simp_controls.querySelector('.simp-next').style.display = 'none';
			simp_others.querySelector('.simp-plext').style.display  = 'none';
			simp_others.querySelector('.simp-random').style.display = 'none';
		}

		// Playlist listeners
		simp_source.forEach(function(item, index) {
			if (item.classList.contains('simp-active')) {
simp_a_index = index; //playlist contains '.simp-active'
			}
			item.addEventListener('click', function() {
				simp_audio.removeEventListener('timeupdate', simp_initTime);
				simp_a_index = index;
				simp_changeAudio(this.querySelector('.simp-source'));
				simp_setAlbum(simp_a_index);
			});
		});

		// FIRST AUDIO LOAD =======
		simp_changeAudio(simp_a_url[simp_a_index]);
		simp_setAlbum(simp_a_index);
		// FIRST AUDIO LOAD =======

		// Controls listeners
		simp_controls.querySelector('.simp-plauseward').addEventListener('click', function(e) {
			var eles = e.target.classList;
			if (eles.contains('simp-plause')) {
				if (simp_audio.paused) {
					if (!simp_isLoaded) {
simp_loadAudio(simp_a_url[simp_a_index]);
					}
					simp_audio.play();
					simp_isPlaying = true;
					e.target.closest( '.simp-controls' ).querySelector('.pause-button').style.display = "block";
					e.target.closest( '.simp-controls' ).querySelector('.play-button').style.display  = "none";
					e.target.closest( '.simple-audio-player' ).classList.add('playing');
				} else {
					simp_audio.pause();
					simp_isPlaying = false;
					e.target.closest( '.simp-controls' ).querySelector('.pause-button').style.display = "none";
					e.target.closest( '.simp-controls' ).querySelector('.play-button').style.display  = "block";
					e.target.closest( '.simple-audio-player' ).classList.remove('playing');
				}
			} else {
				if (eles.contains('simp-prev') && simp_a_index != 0) { 
					simp_a_index      = simp_a_index - 1;
					e.target.disabled = simp_a_index == 0 ? true : false;
				} else if (eles.contains('simp-next') && simp_a_index != simp_a_url.length - 1) {
					simp_a_index      = simp_a_index + 1;
					e.target.disabled = simp_a_index == simp_a_url.length - 1 ? true : false;
				}
				simp_audio.removeEventListener('timeupdate', simp_initTime);
				simp_changeAudio(simp_a_url[simp_a_index]);
				simp_setAlbum(simp_a_index);
			}
		});

		// Audio volume
		simp_volume.addEventListener('click', function(e) {
			var eles = e.target.classList;
			if (eles.contains('simp-mute')) {
				if (  e.target.closest( '.simp-volume' ).querySelector('.volume-off-button').style.display === 'none' ) {
					e.target.closest( '.simp-volume' ).querySelector('.volume-up-button').style.display  = "none";
					e.target.closest( '.simp-volume' ).querySelector('.volume-off-button').style.display = "block";
					simp_v_slider.value = 0;
				} else {
					e.target.closest( '.simp-volume' ).querySelector('.volume-up-button').style.display  = "block";
					e.target.closest( '.simp-volume' ).querySelector('.volume-off-button').style.display = "none";
					simp_v_slider.value = simp_v_num;
				}
			} else {
				simp_v_num = simp_v_slider.value;
				if (simp_v_num != 0) {
					e.target.closest( '.simp-volume' ).querySelector('.volume-up-button').style.display  = "block";
					e.target.closest( '.simp-volume' ).querySelector('.volume-off-button').style.display = "none";
				}
			}
			simp_audio.volume = parseFloat(simp_v_slider.value / 100);
		});

		// Others
		simp_others.addEventListener('click', function(e) {
			var eles = e.target.classList;
			if (eles.contains('simp-plext')) {
				simp_isNext = simp_isNext && !simp_isRandom ? false : true;
				if (!simp_isRandom) {
simp_isRanext = simp_isRanext ? false : true;
				}
				e.target.closest('button').classList.contains('simp-active') && !simp_isRandom ? e.target.closest('button').classList.remove('simp-active') : e.target.closest('button').classList.add('simp-active');
			} else if (eles.contains('simp-random')) {
				simp_isRandom = simp_isRandom ? false : true;
				if (simp_isNext && !simp_isRanext) {
					simp_isNext = false;
					simp_others.querySelector('.simp-plext').closest('button').classList.remove('simp-active');
				} else {
					simp_isNext = true;
					simp_others.querySelector('.simp-plext').closest('button').classList.add('simp-active');
				}
				e.target.closest('button').classList.contains('simp-active') ?  e.target.closest('button').classList.remove('simp-active') :  e.target.closest('button').classList.add('simp-active');
			} else if (eles.contains('simp-shide-top')) {
				simp_album.parentNode.classList.toggle('simp-hide');
			} else if (eles.contains('simp-shide-bottom')) {
				simp_playlist.classList.add('simp-display');
				simp_playlist.classList.toggle('simp-hide');
				e.target.closest('.simp-shide').classList.toggle('playlist-hidden');
			}
		});
	}

	// Start simple player
	if (document.querySelector(selector)) {
		var simp_auto_load, simp_audio, simp_album, simp_cover, simp_title, simp_artist, simp_controls, simp_progress, simp_volume, simp_v_slider, simp_v_num, simp_others;
		var ap_simp        = document.querySelector(selector);
		var simp_playlist  = ap_simp.querySelector('.simp-playlist');
		var simp_source    = simp_playlist.querySelectorAll('li');
		var simp_a_url     = simp_playlist.querySelectorAll('[data-src]');
		var simp_a_index   = 0;
		var simp_isPlaying = false;
		var simp_isNext    = false; //auto play
		var simp_isRandom  = false; //play random
		var simp_isRanext  = false; //check if before random starts, simp_isNext value is true
		var simp_isStream  = false; //radio streaming
		var simp_isLoaded  = false; //audio file has loaded
		var simp_config    = ap_simp.dataset.config ? JSON.parse(ap_simp.dataset.config) : {
			shide_top: false, //show/hide album
			shide_btm: false, //show/hide playlist
			auto_load: false //auto load audio file
		};

		/* SVG Icons for Playlist */

		var music_button = '<svg class="music-button" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve"><path class="st0" d="M470.4,1.5L150.4,96c-13.3,4.2-22.4,16.5-22.4,30.5v261.4c-10.5-2.5-21.2-3.9-32-3.9c-53,0-96,28.7-96,64 s43,64,96,64s96-28.7,96-64V214.3l256-75v184.6c-10.5-2.6-21.2-3.9-32-3.9c-53,0-96,28.7-96,64s43,64,96,64s96-28.6,96-64V32 c0-17.7-14.4-32-32-32C476.7,0,473.5,0.5,470.4,1.5z"/></svg>';

		var prev_button = '<svg class="prev-button simp-prev" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 1024 1024" style="enable-background:new 0 0 1024 1024;" xml:space="preserve"><path class="st0 simp-prev" d="M198.2,526l287.4,248.2c10.7,9.2,26.4,0.9,26.4-14V263.9c0-14.9-15.7-23.2-26.4-14L198.2,498 C189.9,505.2,189.9,518.8,198.2,526z M512,512c0,5.2,2.1,10.4,6.2,14l287.4,248.2c10.7,9.2,26.4,0.9,26.4-14V263.9 c0-14.9-15.7-23.2-26.4-14L518.2,498C514.1,501.6,512,506.8,512,512z"/></svg>';

		var play_button = '<svg class="simp-plause play-button" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 472.6 472.6" style="enable-background:new 0 0 472.6 472.6;" xml:space="preserve"><path class="simp-plause st0" d="M50.3,0 50.3,472.6 422,236.3z "/></svg>';

		var pause_button = '<svg style="display:none;" class="simp-plause pause-button" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 16 16" style="enable-background:new 0 0 16 16;" xml:space="preserve"><path class="simp-plause st0" d="M7,3.7C7,3.3,6.7,3,6.3,3H4.9C4.6,3,4.3,3.3,4.3,3.7v8.6c0,0.4,0.3,0.7,0.7,0.7h1.4C6.7,13,7,12.7,7,12.3L7,3.7	C7,3.7,7,3.7,7,3.7z M11.7,3.7c0-0.4-0.3-0.7-0.7-0.7H9.7C9.3,3,9,3.3,9,3.7v8.6C9,12.7,9.3,13,9.7,13h1.4c0.4,0,0.7-0.3,0.7-0.7 V3.7z"/></svg>';

		var forward_button = '<svg class="forward-button simp-next" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 1024 1024" style="enable-background:new 0 0 1024 1024;" xml:space="preserve"><path class="st0 simp-next" d="M825.8,498L538.4,249.9c-10.7-9.2-26.4-0.9-26.4,14v496.3c0,14.9,15.7,23.2,26.4,14L825.8,526 C834.1,518.8,834.1,505.2,825.8,498z M505.8,498L218.4,249.9c-10.7-9.2-26.4-0.9-26.4,14v496.3c0,14.9,15.7,23.2,26.4,14L505.8,526 c4.1-3.6,6.2-8.8,6.2-14S509.9,501.6,505.8,498z"/></svg>';

		var volume_up_button = '<svg class="simp-mute volume-up-button" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 24 24" style="enable-background:new 0 0 24 24;" xml:space="preserve"><path class="simp-mute st1" d="M18.3,8.4c-0.3-0.4-1-0.5-1.4-0.1s-0.5,1-0.1,1.4c1,1.4,1,3.3,0,4.7c-0.3,0.4-0.3,1.1,0.1,1.4 c0.2,0.1,0.4,0.2,0.6,0.2c0.3,0,0.6-0.1,0.8-0.4C19.9,13.5,19.9,10.5,18.3,8.4z"/><path class="st1" d="M19.6,5.2c-0.4-0.4-1.1-0.3-1.4,0.1s-0.3,1.1,0.1,1.4l0,0C20,8,21,9.9,21,12s-1,4-2.6,5.2 c-0.4,0.4-0.5,1-0.1,1.4l0,0c0.2,0.2,0.5,0.4,0.8,0.4c0.2,0,0.5-0.1,0.6-0.2c2.1-1.6,3.3-4.1,3.4-6.8C23,9.4,21.7,6.9,19.6,5.2z"/><path class="st1" d="M14.5,3.1c-0.3-0.2-0.7-0.2-1,0L7,7.6H2c-0.6,0-1,0.4-1,1v6.9c0,0.6,0.4,1,1,1h5l6.4,4.4 c0.2,0.1,0.4,0.2,0.6,0.2c0.6,0,1-0.4,1-1V4C15,3.6,14.8,3.3,14.5,3.1z"/></svg>';

		var volume_off_button = '<svg style="display:none;" class="simp-mute volume-off-button" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 24 24" style="enable-background:new 0 0 24 24;" xml:space="preserve"><path class="simp-mute st1" d="M14.5,3.1c-0.3-0.2-0.7-0.2-1,0L7,7.6H2c-0.6,0-1,0.4-1,1v6.9c0,0.6,0.4,1,1,1h5l6.4,4.4 c0.2,0.1,0.4,0.2,0.6,0.2c0.6,0,1-0.4,1-1V4C15,3.6,14.8,3.3,14.5,3.1z"/></svg>';

		var auto_play_button = '<svg class="auto-play-button simp-plext" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 485.7 485.7" style="enable-background:new 0 0 485.7 485.7;" xml:space="preserve"><path class="st0 simp-plext" d="M242.9,0C108.7,0,0,108.7,0,242.9C0,377,108.7,485.7,242.9,485.7c134.1,0,242.9-108.7,242.9-242.9 C485.7,108.7,377,0,242.9,0z M338.4,263.9L204,356.6c-16.8,11.6-30.6,4.2-30.6-16.3v-195c0-20.6,13.8-27.9,30.6-16.3l134.3,92.7 C355.1,233.4,355.2,252.3,338.4,263.9z"/></svg>';

		var shuffle_button = '<svg class="shuffle-button simp-random" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 95.5 95.5" style="enable-background:new 0 0 95.5 95.5;" xml:space="preserve"><path class="st0 simp-random" d="M68.9,36.5h6.6V40c0,0.7,0.4,1.3,1,1.7c0.6,0.3,1.3,0.3,1.9,0l16.1-9.3c0.6-0.3,1-1,1-1.7s-0.4-1.3-1-1.7 l-16.1-9.3c-0.6-0.3-1.3-0.3-1.9,0s-1,1-1,1.7v3.5h-6.7c-10.2,0-17.7,9.5-24.9,18.6C38,51.1,31.8,59,25.9,59h-24c-1,0-1.9,0.9-1.9,2 v7.8c0,1.1,0.9,1.9,1.9,1.9h24c11.5,0,19.8-10.6,27.2-20C58.6,43.7,64.3,36.5,68.9,36.5z"/><path class="st0" d="M94.6,63.1l-16.1-9.3c-0.6-0.3-1.3-0.3-1.9,0s-1,1-1,1.7V59H69c-3.5,0-7.7-4.5-10.6-7.8l-7.8,8.7 c4.4,5,10.6,10.8,18.4,10.8h6.7v3.5c0,0.7,0.4,1.3,1,1.7c0.3,0.2,0.6,0.3,1,0.3c0.3,0,0.7-0.1,1-0.3l16.1-9.3c0.6-0.3,1-1,1-1.7 C95.5,64.1,95.2,63.5,94.6,63.1z"/><path class="st0" d="M25.9,24.9h-24c-1.1,0-1.9,0.9-1.9,1.9v7.8c0,1.1,0.9,1.9,1.9,1.9h24c4.3,0,8.8,4.5,11.8,7.9l7.7-8.7 C41.2,31,34.6,24.9,25.9,24.9z"/></svg>';

		var caret_up_button = '<svg class="caret-up-button simp-shide-top" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 52 52" style="enable-background:new 0 0 52 52;" xml:space="preserve"><path class="st0 simp-shide-top" d="M7.3,35.8l17.5-21.2c0.6-0.8,1.9-0.8,2.5,0l17.3,21.2c0.8,0.9,0.1,2.2-0.9,2.2H8.3C7.2,38,6.6,36.7,7.3,35.8z"	/></svg>';

		var caret_down_button = '<svg class="caret-down-button simp-shide-bottom" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 52 52" style="enable-background:new 0 0 52 52;" xml:space="preserve"><path class="st0 simp-shide-bottom" d="M8.3,14h35.4c1,0,1.7,1.3,0.9,2.2L27.3,37.4c-0.6,0.8-1.9,0.8-2.5,0L7.3,16.2C6.6,15.3,7.2,14,8.3,14z"/></svg>';


		var simp_elem = '';
		simp_elem    += '<audio class="audio" preload><source src="" type="audio/mpeg"></audio>';
		simp_elem    += '<div class="simp-display"><div class="simp-album w-full flex-wrap"><div class="simp-cover">'+ music_button +'</div><div class="simp-info"><div class="simp-title">Title</div><div class="simp-artist">Artist</div></div></div></div>';
		simp_elem    += '<div class="simp-controls flex-wrap flex-align">';
		simp_elem    += '<div class="simp-plauseward flex flex-align"><button type="button" class="simp-prev" disabled>'+ prev_button +'</button><button type="button" class="simp-plause" disabled>'+ play_button + pause_button +'</button><button type="button" class="simp-next" disabled>'+ forward_button +'</button></div>';
		simp_elem    += '<div class="simp-tracker simp-load"><input class="simp-progress" type="range" min="0" max="100" value="0" disabled/><div class="simp-buffer"></div></div>';
  /*       simp_elem += '<div class="simp-link"><button type="button" class="fa fa-link fa-flip-horizontal simp-no-hover" title="Copy audio url at current time"></button></div>'; */
		simp_elem += '<div class="simp-time flex flex-align"><span class="start-time">00:00</span><span class="simp-slash">&#160;/&#160;</span><span class="end-time">00:00</span></div>';
		simp_elem += '<div class="simp-volume flex flex-align"><button type="button" class="simp-mute">'+ volume_up_button + volume_off_button +'</button><input class="simp-v-slider" type="range" min="0" max="100" value="100"/></div>';
		simp_elem += '<div class="simp-others flex flex-align"><button type="button" class="simp-plext" title="Auto Play">'+ auto_play_button +'</button><button type="button" class="simp-random" title="Random">'+ shuffle_button +'</button><div class="simp-shide"><button type="button" class="simp-shide-top" title="Show/Hide Album">'+ caret_up_button +'</button><button type="button" class="simp-shide-bottom" title="Show/Hide Playlist">'+ caret_down_button +'</button></div></div>';
		simp_elem += '</div>'; //simp-controls

		if (ap_simp.querySelector('.simp-player')) {
			ap_simp.removeChild(ap_simp.querySelector('.simp-player'));
		}

		var simp_player = document.createElement('div');
		simp_player.classList.add('simp-player');
		simp_player.innerHTML = simp_elem;
		ap_simp.insertBefore(simp_player, simp_playlist);
		simp_startScript();
	}
}
