jQuery(document).ready(function($){
	$('.rbjs-rating-box').each(function(){
		var rating_box = $(this),
			post_id = parseInt(rating_box.attr('data-post-id'));
		
		$('li',this).each(function(index){
			$(this).hover(function(){
				for(i=0;i<5;i++){
					if( i<=index ){
						rating_box.find('li').eq(i).addClass('hover');
					} else {
						rating_box.find('li').eq(i).removeClass('hover');
					}
				}
			}, function(){
				$(this).removeClass('hover');
			}).click(function(){			
				if( typeof admin_ajax_url == 'string' ){
					
					$.post( admin_ajax_url, { 
								action : 'submit_star', 
								post_id: post_id, 
								star_value: index+1 
							},function( response ) {
								console.log( response );
								
								// location.href = location.href;
								
								// window.reload();
							});
				}
			});
			
		});
		
		rating_box.hover(function(){
			$(this).addClass('hover');
		}, function(){
			$(this).removeClass('hover');
			rating_box.find('li.hover').removeClass('hover');
		});
		
	});
	
} );