document.addEventListener('DOMContentLoaded', function() {
	const widget = this.querySelector('.widget');
	const buttons = document.querySelectorAll('.widget label');
    const postsContainer = document.querySelector('.widget ul');

    if(buttons.length > 0) {
		buttons.forEach(button => {
			
			button.addEventListener('click', function(e) {
				postsContainer.innerHTML = '<p>Loading...</p>';
				widget.classList.add('throttle');
				setTimeout(() => {

				const posttype = document.querySelector('.widget ul').dataset.posttype;
				const currentpost = document.querySelector('.widget ul').dataset.post;
				console.log(currentpost);
				const cats = getSelectedCheckboxes('cat-filter');
				const tags = getSelectedCheckboxes('tag-filter');
		
				// Prepare the data to send via AJAX
				const data = new FormData();
				data.append('action', 'get_custom_posts'); // Action hook
				data.append('nonce', ajax_object.nonce); // Security nonce
				data.append('post_type', posttype); // post type
				data.append('cats', cats); 
				data.append('tags', tags); 
				data.append('post__not__in', currentpost); 

		
				// Create the AJAX request
				const xhr = new XMLHttpRequest();
				xhr.open('POST', ajax_object.ajax_url, true);
		
				
			
				// Define what happens on successful data submission
				xhr.onload = function () {
					widget.classList.remove('throttle');
					if (xhr.status === 200) {
						// Update the posts container with the received HTML
						postsContainer.innerHTML = xhr.responseText;
					} else {
						// Handle error
						postsContainer.innerHTML = '<li>Error</li>';
					}
				};
		
				// Send the request with the data
				xhr.send(data);
			}, 400);
			});
		})
	
	}
});

// Function to get selected checkboxes in a given category
function getSelectedCheckboxes(category) {
	const checkboxes = document.querySelectorAll(`#${category} input[type="checkbox"]`);
	const selected = [];

	checkboxes.forEach(function(checkbox) {
		if (checkbox.checked) {
			selected.push(checkbox.value); // You can also use checkbox.id if you need the id
		}
	});

	return selected;
}
