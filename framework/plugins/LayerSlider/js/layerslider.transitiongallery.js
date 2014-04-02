(function($) {

	$.fn.transitionGallery = function( options ){

		if( (typeof(options)).match('object|undefined') ){
			return this.each(function(i){
				new transitionGallery(this, options );
			});
		}else{
			return this.each(function(i){

				var tg = $(this).data('TransitionGallery');

				switch(options){
					case 'destroy':
						tg.destroy();
					break;
				}
			});
		}
	};

	var transitionGallery = function(el, options) {

		var tg = this;
		tg.$el = $(el).addClass('ls-lt-container');
		tg.$el.data('TransitionGallery', tg);
		tg.g = {
			slide : ['sample_slide_1.png','sample_slide_2.png'],
			cssTransitions : !lsBrowser().msie || ( lsBrowser().msie && lsBrowser().version > 9 ) ? true : false
		};
		tg.o = options;

		tg.destroy = function(){
			clearTimeout( tg.g.timer1 );
			clearTimeout( tg.g.timer2 );
			$(el).find('*').stop().dequeue();
			$(el).empty();
		};

		tg.animate = function(){

			var cur = 0, next;
			
			var slideTransition = function(){

				next = cur == tg.g.slide.length - 1 ? 0 : cur + 1;

				var carousel = tg.o.transition.name.toLowerCase().indexOf('carousel') == -1 ? false : true;

				// Calculating cols and rows

				var cols = 1;

				if( typeof tg.o.transition.cols  == 'number' ){
					cols = tg.o.transition.cols;
				}else if( typeof tg.o.transition.cols == 'string' ){
					cols = Math.floor( Math.random() * ( parseInt( tg.o.transition.cols.split(',')[1] ) - parseInt( tg.o.transition.cols.split(',')[0] ) + 1) ) + parseInt( tg.o.transition.cols.split(',')[0] );
				}else if( typeof tg.o.transition.cols == 'object' ){
					cols = Math.floor( Math.random() * ( tg.o.transition.cols[1] - tg.o.transition.cols[0] + 1) ) + tg.o.transition.cols[0];
				}

				var rows = 1;

				if( typeof tg.o.transition.rows == 'number' ){
					rows = tg.o.transition.rows;					
				}else if( typeof tg.o.transition.rows == 'string' ){
					rows = Math.floor( Math.random() * ( parseInt( tg.o.transition.rows.split(',')[1] ) - parseInt( tg.o.transition.rows.split(',')[0] ) + 1) ) + parseInt( tg.o.transition.rows.split(',')[0] );	
				}else if( typeof tg.o.transition.rows == 'object' ){
					rows = Math.floor( Math.random() * ( tg.o.transition.rows[1] - tg.o.transition.rows[0] + 1) ) + tg.o.transition.rows[0];					
				}

				var tileWidth = $(el).width() / cols;
				var tileHeight = $(el).height() / rows;

				// Setting size

				var restW = $(el).width() - Math.floor(tileWidth) * cols;
				var restH = $(el).height() - Math.floor(tileHeight) * rows;

				var tileSequence = [];

				for(var ts=0; ts<cols * rows; ts++){
					tileSequence.push(ts);
				}

				// Setting the sequences of the transition

				switch( tg.o.transition.tile.sequence ){
					case 'reverse':
						tileSequence.reverse();
					break;
					case 'col-forward':
						tileSequence = lsOrderArray(rows,cols,'forward');
					break;
					case 'col-reverse':
						tileSequence = lsOrderArray(rows,cols,'reverse');
					break;
					case 'random':
						tileSequence.randomize();
					break;
				}

				if( tg.o.type == '3d' ){
					tg.g.totalDuration = ((cols * rows) - 1) * tg.o.transition.tile.delay;

					var stepDuration = 0;

					if( tg.o.transition.before && tg.o.transition.before.duration ){
						stepDuration += tg.o.transition.before.duration;
					}
					if( tg.o.transition.animation && tg.o.transition.animation.duration ){
						stepDuration += tg.o.transition.animation.duration;
					}
					if( tg.o.transition.after && tg.o.transition.after.duration ){
						stepDuration += tg.o.transition.after.duration;
					}

					tg.g.totalDuration += stepDuration;

					var stepDelay = 0;

					if( tg.o.transition.before && tg.o.transition.before.delay ){
						stepDelay += tg.o.transition.before.delay;
					}
					if( tg.o.transition.animation && tg.o.transition.animation.delay ){
						stepDelay += tg.o.transition.animation.delay;
					}
					if( tg.o.transition.after && tg.o.transition.after.delay ){
						stepDelay += tg.o.transition.after.delay;
					}

					tg.g.totalDuration += stepDelay;

				}else{
					tg.g.totalDuration = ((cols * rows) - 1) * tg.o.transition.tile.delay + tg.o.transition.transition.duration;

					tg.g.curTiles = $('<div>').addClass('ls-curtiles').appendTo( tg.$el );
					tg.g.nextTiles = $('<div>').addClass('ls-nexttiles').appendTo( tg.$el );
				}

				// Creating cuboids for 3d or tiles for 2d transition (cols * rows)

				for(var tiles=0; tiles < cols * rows; tiles++){

					var rW = tiles%cols == 0 ? restW : 0;
					var rH = tiles > (rows-1)*cols-1 ? restH : 0;

					var tile = $('<div>').addClass('ls-lt-tile').css({
						width : Math.floor(tileWidth) + rW,
						height : Math.floor(tileHeight) + rH
					}).appendTo( $(el) );

					var curTile, nextTile;

					// If current transition is a 3d transition

					if( tg.o.type == '3d' ){

						tile.addClass('ls-3d-container');

						var W = Math.floor(tileWidth) + rW;
						var H = Math.floor(tileHeight) + rH;
						var D;

						if( tg.o.transition.animation.direction == 'horizontal' ){
							if( Math.abs(tg.o.transition.animation.transition.rotateY) > 90 && tg.o.transition.tile.depth != 'large' ){
								D = 7 + rW;
							}else{
								D = W;
							}
						}else{
							if( Math.abs(tg.o.transition.animation.transition.rotateX) > 90 && tg.o.transition.tile.depth != 'large' ){
								D = 7 + rH;
							}else{
								D = H;
							}
						}

						var W2 = W/2;
						var H2 = H/2;
						var D2 = D/2;

						// createCuboids function will append cuboids with their style settings to their container

						var createCuboids = function(c,a,w,h,tx,ty,tz,rx,ry){
							$('<div>').addClass(c).css({
								width: w,
								height: h,
								'transform': 'translate3d('+tx+'px, '+ty+'px, '+tz+'px) rotateX('+rx+'deg) rotateY('+ry+'deg) rotateZ(0deg) scale3d(1, 1, 1)',
								'-o-transform': 'translate3d('+tx+'px, '+ty+'px, '+tz+'px) rotateX('+rx+'deg) rotateY('+ry+'deg) rotateZ(0deg) scale3d(1, 1, 1)',
								'-ms-transform': 'translate3d('+tx+'px, '+ty+'px, '+tz+'px) rotateX('+rx+'deg) rotateY('+ry+'deg) rotateZ(0deg) scale3d(1, 1, 1)',
								'-moz-transform': 'translate3d('+tx+'px, '+ty+'px, '+tz+'px) rotateX('+rx+'deg) rotateY('+ry+'deg) rotateZ(0deg) scale3d(1, 1, 1)',
								'-webkit-transform': 'translate3d('+tx+'px, '+ty+'px, '+tz+'px) rotateX('+rx+'deg) rotateY('+ry+'deg) rotateZ(0deg) scale3d(1, 1, 1)'
							}).appendTo(a);
						};

						createCuboids('ls-3d-box',tile,0,0,0,0,-D2,0,0);

						var backRotX = 0
						var topRotX = 0
						var bottomRotX = 0

						if( tg.o.transition.animation.direction == 'vertical' && Math.abs(tg.o.transition.animation.transition.rotateX) > 90){
							createCuboids('ls-3d-back',tile.find('.ls-3d-box'),W,H,-W2,-H2,-D2,180,0);
						}else{
							createCuboids('ls-3d-back',tile.find('.ls-3d-box'),W,H,-W2,-H2,-D2,0,180);								
						}

						createCuboids('ls-3d-bottom',tile.find('.ls-3d-box'),W,D,-W2,H2-D2,0,-90,0);
						createCuboids('ls-3d-top',tile.find('.ls-3d-box'),W,D,-W2,-H2-D2,0,90,0);
						createCuboids('ls-3d-front',tile.find('.ls-3d-box'),W,H,-W2,-H2,D2,0,0);
						createCuboids('ls-3d-left',tile.find('.ls-3d-box'),D,H,-W2-D2,-H2,0,0,-90);
						createCuboids('ls-3d-right',tile.find('.ls-3d-box'),D,H,W2-D2,-H2,0,0,90);

						curTile = tile.find('.ls-3d-front');

						if( tg.o.transition.animation.direction == 'horizontal' ){
							if( Math.abs(tg.o.transition.animation.transition.rotateY) > 90 ){
								nextTile = tile.find('.ls-3d-back');
							}else{
								nextTile = tile.find('.ls-3d-left, .ls-3d-right');
							}
						}else{
							if( Math.abs(tg.o.transition.animation.transition.rotateX) > 90 ){
								nextTile = tile.find('.ls-3d-back');
							}else{
								nextTile = tile.find('.ls-3d-top, .ls-3d-bottom');
							}
						}

						$(el).css({
							background: 'none'
						});

						// Animating cuboids

						var curCubDelay = tileSequence[tiles] * tg.o.transition.tile.delay;

						var curCub = $(el).find('.ls-3d-container:eq('+tiles+') .ls-3d-box');

						if( tg.o.transition.before && tg.o.transition.before.transition ){
							tg.o.transition.before.transition.delay = tg.o.transition.before.transition.delay ? tg.o.transition.before.transition.delay + curCubDelay : curCubDelay;
							curCub.transition( tg.o.transition.before.transition, tg.o.transition.before.duration, tg.o.transition.before.easing );
						}else{
							tg.o.transition.animation.transition.delay = tg.o.transition.animation.transition.delay ? tg.o.transition.animation.transition.delay + curCubDelay : curCubDelay;
						}

						curCub.transition( tg.o.transition.animation.transition, tg.o.transition.animation.duration, tg.o.transition.animation.easing )

						if( tg.o.transition.after ){
							curCub.transition( $.extend({},{ scale3d : 1 }, tg.o.transition.after.transition), tg.o.transition.after.duration, tg.o.transition.after.easing );
						}
					
					}else{

						// If current transition is a 2d transition

						var T1 = L1 = T2 = L2 = 'auto';
						var O1 = O2 = 1;

						if( tg.o.transition.transition.direction == 'random' ){
							var dir = ['top','bottom','right','left'];
							var direction = dir[Math.floor(Math.random() * dir.length )];
						}else{
							var direction = tg.o.transition.transition.direction;
						}

						// Selecting direction

						var pn = 'next';
						
						if( tg.o.transition.name.toLowerCase().indexOf('mirror') != -1 && tiles%2 == 0 ){
							pn = 'prev';
						}

						if( pn == 'prev' ){

							switch( direction ){
								case 'top':
									direction = 'bottom';
								break;
								case 'bottom':
									direction = 'top';
								break;
								case 'left':
									direction = 'right';
								break;
								case 'right':
									direction = 'left';
								break;
								case 'topleft':
									direction = 'bottomright';
								break;
								case 'topright':
									direction = 'bottomleft';
								break;
								case 'bottomleft':
									direction = 'topright';
								break;
								case 'bottomright':
									direction = 'topleft';
								break;
							}
						}

						switch( direction ){
							case 'top':
								T1 = T2 = -tile.height();
								L1 = L2 = 0;
							break;
							case 'bottom':
								T1 = T2 = tile.height();
								L1 = L2 = 0;
							break;
							case 'left':
								T1 = T2 = 0;
								L1 = L2 = -tile.width();
							break;
							case 'right':
								T1 = T2 = 0;
								L1 = L2 = tile.width();
							break;
							case 'topleft':
								T1 = tile.height();
								T2 = 0;
								L1 = tile.width(); 
								L2 = 0;
							break;
							case 'topright':
								T1 = tile.height();
								T2 = 0;
								L1 = - tile.width(); 
								L2 = 0;
							break;
							case 'bottomleft':
								T1 = - tile.height();
								T2 = 0;
								L1 = tile.width(); 
								L2 = 0;
							break;
							case 'bottomright':
								T1 = - tile.height();
								T2 = 0;
								L1 = - tile.width(); 
								L2 = 0;
							break;
						}

						tg.g.scale2D = tg.o.transition.transition.scale ? tg.o.transition.transition.scale : 1;

						if( carousel == true && tg.g.scale2D != 1 ){
							
							T1 = T1 / 2;
							T2 = T2 / 2;
							L1 = L1 / 2;
							L2 = L2 / 2;
						}

						// Selecting the type of the transition

						var ie78 = lsBrowser().msie && lsBrowser().version < 9 ? true : false;

//						if( !ie78 || ( ie78 && tg.o.transition.name.toLowerCase().indexOf('crossfade') != -1 ) ){
							switch( tg.o.transition.transition.type ){
								case 'fade':
									T1 = T2 = L1 = L2 = 0;
									O1 = 0;
									O2 = 1;
								break;
								case 'mixed':
								O1 = 0;
								O2 = 1;
								if( tg.g.scale2D == 1 ){
									T2 = L2 = 0;
								}
								break;
							}
//						}

						if((( tg.o.transition.transition.rotate || tg.o.transition.transition.rotateX || tg.o.transition.transition.rotateY ) || tg.g.scale2D != 1 ) && !ie78 && tg.o.transition.transition.type != 'slide' ){
							tile.css({
								overflow : 'visible'
							});
						}else{
							tile.css({
								overflow : 'hidden'
							});									
						}
						
						if( carousel == true){
							tg.g.curTiles.css({
								overflow: 'visible'
							});
						}else{
							tg.g.curTiles.css({
								overflow: 'hidden'
							});									
						}

						if( tg.o.transition.transition.type == 'slide' || carousel == true ){
							var tileInCur = tile.appendTo( tg.g.curTiles );
							curTile = $('<div>').addClass('ls-curtile').appendTo( tileInCur );
							var tileInNext = tile.clone().appendTo( tg.g.nextTiles );
						}else{
							var tileInNext = tile.appendTo( tg.g.nextTiles );
						}

						nextTile = $('<div>').addClass('ls-nexttile').appendTo( tileInNext ).css({
							top : -T1,
							left : -L1,
							dispay : 'block',
							opacity : O1
						});

						// Animating tiles

						var curTileDelay = tileSequence[tiles] * tg.o.transition.tile.delay;

						$(el).css({
							background: 'none'
						});

						if( tg.g.cssTransitions && $.transit != undefined && tg.o.transition.transition.easing.indexOf('swing') == -1 && tg.o.transition.transition.easing.indexOf('Elastic') == -1 && tg.o.transition.transition.easing.indexOf('Bounce') == -1 ){
							var r = tg.o.transition.transition.rotate ? tg.o.transition.transition.rotate : 0;
							var rX = tg.o.transition.transition.rotateX ? tg.o.transition.transition.rotateX : 0;
							var rY = tg.o.transition.transition.rotateY ? tg.o.transition.transition.rotateY : 0;
							
							if( pn == 'prev' ){
								r = -r;
								rX = -rX;
								rY = -rY;
							}

							if( rX != 0 || rY != 0 || r != 0 || tg.g.scale2D != 1 ){
								nextTile.css({
									'transform': 'rotate('+r+'deg) rotateX('+rX+'deg) rotateY('+rY+'deg) scale('+tg.g.scale2D+','+tg.g.scale2D+')',
									'-o-transform': 'rotate('+r+'deg) rotateX('+rX+'deg) rotateY('+rY+'deg) scale('+tg.g.scale2D+','+tg.g.scale2D+')',
									'-ms-transform': 'rotate('+r+'deg) rotateX('+rX+'deg) rotateY('+rY+'deg) scale('+tg.g.scale2D+','+tg.g.scale2D+')',
									'-moz-transform': 'rotate('+r+'deg) rotateX('+rX+'deg) rotateY('+rY+'deg) scale('+tg.g.scale2D+','+tg.g.scale2D+')',
									'-webkit-transform': 'rotate('+r+'deg) rotateX('+rX+'deg) rotateY('+rY+'deg) scale('+tg.g.scale2D+','+tg.g.scale2D+')'
								});										
							}
							
							nextTile.transition({
								delay : curTileDelay,
								top : 0,
								left : 0,
								opacity : O2,
								rotate : 0,
								rotateX : 0,
								rotateY : 0,
								scale : 1
							}, tg.o.transition.transition.duration, tg.o.transition.transition.easing );

							if( ( tg.o.transition.transition.type == 'slide' || carousel == true ) && tg.o.transition.name.toLowerCase().indexOf('mirror') == -1 ){
																		
								var r2 = 0;

								if( r != 0 ){
									r2 = -r;
								}

								curTile.transition({
									delay : curTileDelay,
									top : T2,
									left : L2,
									rotate : r2,
									scale : tg.g.scale2D,
									opacity: O1
								}, tg.o.transition.transition.duration, tg.o.transition.transition.easing );
							}
						}else{
							nextTile.delay( curTileDelay ).animate({
								top : 0,
								left : 0,
								opacity : O2
							}, tg.o.transition.transition.duration, tg.o.transition.transition.easing );
							curTile.delay( curTileDelay ).animate({
								top : T2,
								left : L2
							}, tg.o.transition.transition.duration, tg.o.transition.transition.easing );								
						}
					}

					// Appending the background images of current and next layers into the tiles on both of 2d & 3d transitions

					if( tg.o.type == '3d' || ( tg.o.type == '2d' && ( tg.o.transition.transition.type == 'slide' || carousel == true ) ) ){
						curTile.append($('<img>').attr('src', tg.o.path+tg.g.slide[cur] ).css({
							marginLeft : - parseInt(tile.position().left),
							marginTop :  - parseInt(tile.position().top)
						}));						
					}else{
						tg.g.curTiles.append($('<img>').attr('src', tg.o.path+tg.g.slide[cur] ).css({
							marginLeft : - parseInt(tile.position().left),
							marginTop :  - parseInt(tile.position().top)
						}));
						
					}

					nextTile.append($('<img>').attr('src', tg.o.path+tg.g.slide[next] ).css({
						marginLeft :  - parseInt(tile.position().left),
						marginTop :  - parseInt(tile.position().top)
					}));
				}

				next = cur;
				cur = cur == tg.g.slide.length - 1 ? 0 : cur + 1;

				tg.g.timer1 = setTimeout(function(){
					$(el).css({
						background: 'url('+(tg.o.path+tg.g.slide[cur])+')'
					}).empty();
				}, tg.g.totalDuration );
				
				tg.g.timer2 = setTimeout(function(){
					slideTransition();					
				}, tg.o.delay + tg.g.totalDuration );
			};		

			slideTransition(true);
		};

		if( tg.o.type == '3d' && !lsSupport3D($(el)) ){
			$(el).css({
				background: 'url('+tg.o.path+'nocss3d.png)'
			})
		}else{
			tg.animate();
		}
	};

	// Support3D checks the CSS3 3D capability of the browser (based on the idea of Modernizr.js)

	var lsSupport3D = function( el ) {
		
		var testEl = $('<div>'),
			s3d1 = false,
			s3d2 = false,
			properties = ['perspective', 'OPerspective', 'msPerspective', 'MozPerspective', 'WebkitPerspective'];
			transform = ['transformStyle','OTransformStyle','msTransformStyle','MozTransformStyle','WebkitTransformStyle'];

		for (var i = properties.length - 1; i >= 0; i--){
			s3d1 = s3d1 ? s3d1 : testEl[0].style[properties[i]] != undefined;
		};
		
		// preserve 3D test
		
		for (var i = transform.length - 1; i >= 0; i--){
			testEl.css( 'transform-style', 'preserve-3d' );
			s3d2 = s3d2 ? s3d2 : testEl[0].style[transform[i]] == 'preserve-3d';
		};

		// If browser has perspective capability and it is webkit, we must check it with this solution because Chrome can give false positive result if GPU acceleration is disabled

        if (s3d1 && testEl[0].style[properties[4]] != undefined){
			testEl.attr('id','ls-test3d').appendTo( el );
            s3d1 = testEl[0].offsetHeight === 3 && testEl[0].offsetLeft === 9;
			testEl.remove();
        }

        return (s3d1 && s3d2);
	};

	// Order array function

	var lsOrderArray = function(x,y,dir) {
		var i = [];
		if(dir=='forward'){
			for( var a=0; a<x;a++){
				for( var b=0; b<y; b++){
					i.push(a+b*x);	
				}
			}
		}else{
			for( var a=x-1; a>-1;a--){
				for( var b=y-1; b>-1; b--){
					i.push(a+b*x);
				}
			}
		}
		return i;
	};

	// Randomize array function

	Array.prototype.randomize = function() {
	  var i = this.length, j, tempi, tempj;
	  if ( i == 0 ) return false;
	  while ( --i ) {
	     j       = Math.floor( Math.random() * ( i + 1 ) );
	     tempi   = this[i];
	     tempj   = this[j];
	     this[i] = tempj;
	     this[j] = tempi;
	  }
	  return this;
	}

	// CountProp counts the properties in an object

	var lsCountProp = function(obj) {
	    var count = 0;

	    for(var prop in obj) {
	        if(obj.hasOwnProperty(prop)){
	            ++count;
			}
	    }
	    return count;
	};
	
	// We need the browser function (removed from jQuery 1.9)

	var lsBrowser = function(){

		uaMatch = function( ua ) {
			ua = ua.toLowerCase();

			var match = /(chrome)[ \/]([\w.]+)/.exec( ua ) ||
				/(webkit)[ \/]([\w.]+)/.exec( ua ) ||
				/(opera)(?:.*version|)[ \/]([\w.]+)/.exec( ua ) ||
				/(msie) ([\w.]+)/.exec( ua ) ||
				ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec( ua ) ||
				[];

			return {
				browser: match[ 1 ] || "",
				version: match[ 2 ] || "0"
			};
		};

		var matched = uaMatch( navigator.userAgent ), browser = {};

		if ( matched.browser ) {
			browser[ matched.browser ] = true;
			browser.version = matched.version;
		}

		if ( browser.chrome ) {
			browser.webkit = true;
		} else if ( browser.webkit ) {
			browser.safari = true;
		}
		return browser;			
	};
})(jQuery);