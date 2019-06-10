$(document).ready(function(e) {
    $('[data-toggle="tooltip"]').tooltip();   
 $('.testimonial-block').owlCarousel({
			  loop: true,
			  margin: 10,
			  nav: false,
			  dots: false,
			  autoplay: true,
			  autoplayTimeout: 5000,
			  responsive: {
				  0: {
					  items: 1
				  },
				  600: {
					  items: 1
				  },
				  1000: {
					  items: 1
				  }
			  }
		  })  
		  

	
				$('.join_the_movement_testimonial').owlCarousel({
					loop: true,
					margin: 10,
					nav: false,
					dots: false,
					autoplay: true,
					autoplayTimeout: 5000,
					responsive: {
						0: {
							items: 1
						},
						600: {
							items: 1
						},
						1000: {
							items: 3
						}
					}
				})

 
});

