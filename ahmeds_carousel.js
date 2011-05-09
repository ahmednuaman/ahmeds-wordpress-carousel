var AHMEDS_CAROUSEL	= {
	animationEase												: 'easeInOutQuint',
	animationIntervalTime										: 6000,
	animationTime												: 1000,
	checkInterval												: null,
	hasjQuery													: true,
	jqueryQueue													: [ ],
	
	checkForjQuery												: function()
	{
		if ( typeof jQuery != 'undefined' )
		{
			clearInterval( AHMEDS_CAROUSEL.checkInterval );

			AHMEDS_CAROUSEL.jqueryQueue.forEach( function(o)
			{
				o.f.apply( null, o.a );
			});
		}
	},
	
	startCarousel												: function()
	{
		if ( !AHMEDS_CAROUSEL.hasjQuery )
		{
			AHMEDS_CAROUSEL.jqueryQueue.push( AHMEDS_CAROUSEL.startCarousel );
			
			return;
		}
		
		var c	= jQuery( '.ahmeds_carousel' );

		if ( c.length > 0 )
		{
			var u	= jQuery( 'ul', c );
			var m   = jQuery( 'li', c ).length;
			var w   = jQuery( 'li:first', c ).outerWidth();
			var i   = 0;

			u.width( m * w );

			setInterval( function()
			{
				if ( i >= m )
				{
					i   = 0;
				}

				u.animate({
					'margin-left'   : i * w * -1 + 'px'
				}, AHMEDS_CAROUSEL.animationTime, AHMEDS_CAROUSEL.animationEase );

				i++;
		    }, AHMEDS_CAROUSEL.animationIntervalTime );
		}
	}
};

AHMEDS_CAROUSEL.checkForjQuery();

if ( typeof jQuery == 'undefined' )
{
	AHMEDS_CAROUSEL.checkInterval	= setInterval( AHMEDS_CAROUSEL.checkForjQuery, 250 );
	
	document.write( '<scr' + 'ipt src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.0/jquery.min.js"></scr' + 'ipt>' );
	document.write( '<scr' + 'ipt src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.12/jquery-ui.min.js"></scr' + 'ipt>' );
}