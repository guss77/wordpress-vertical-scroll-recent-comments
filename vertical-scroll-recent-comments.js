/**
 *     Vertical scroll recent comments
 *     Copyright (C) 2011 - 2021 www.gopiplus.com
 *     http://www.gopiplus.com/work/2010/07/18/vertical-scroll-recent-comments/
 * 
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 * 
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 * 
 *     You should have received a copy of the GNU General Public License
 *     along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */	

function vsrc_scroll() {
	clearTimeout(window.vsrc_config.timer);
	var container = window.vsrc_config.container;
	container.scrollTop = container.scrollTop + 1;
	window.vsrc_config.scrollPos++;
	if ((window.vsrc_config.scrollPos % window.vsrc_config.elmHeight) == 0) {
		window.vsrc_config.numScrolls--;
		if (window.vsrc_config.numScrolls == 0) {
			container.scrollTop = '0';
			vsrc_content();
		} else {
			if (window.vsrc_config.scrollOn) {
				vsrc_content();
			}
		}
	} else {
		var speed = 60 - ( window.vsrc_config.speed * 10 );
		window.vsrc_config.timer = setTimeout(vsrc_scroll, speed);
	}
}

/*
Creates amount to show + 1 for the scrolling ability to work
scrollTop is set to top position after each creation
Otherwise the scrolling cannot happen
*/
function vsrc_content(config) {
	if (config) {
		window.vsrc_config = config;
		window.vsrc_config.scrollPos = 0;
		window.vsrc_config.scrollOn = true;
		window.vsrc_config.displayNum = 0;
	}
	var tmp_vsrc = '',
		container = window.vsrc_config.container,
		list = window.vsrc_config.comments;

	w_vsrc = window.vsrc_config.displayNum - window.vsrc_config.elmCount;
	if (w_vsrc < 0) {
		w_vsrc = 0;
	} else {
		w_vsrc = w_vsrc % list.length;
	}
	
	// Show amount of vsrru
	var elementsTmp_vsrc = window.vsrc_config.elmCount + 1;
	for (i_vsrc = 0; i_vsrc < elementsTmp_vsrc; i_vsrc++) {
		
		tmp_vsrc += list[w_vsrc % list.length];
		w_vsrc++;
	}

	container.innerHTML           = tmp_vsrc;
	container.scrollTop          = '0';
	window.vsrc_config.displayNum = w_vsrc;
	window.vsrc_config.numScrolls = list.length;
	// start scrolling
	window.vsrc_config.timer = setTimeout(vsrc_scroll, window.vsrc_config.waitSec * 1000, );
}

jQuery(document).ready(function($) {
	typeof vsrc_createscroll == 'function' && vsrc_createscroll();
});
