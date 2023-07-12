//snipping.js





 // Event listener for page load event
 window.addEventListener('load', function() {
    // Get the container element
    var container = document.getElementById('snipping-container');

    // Set the HTML of the container to the snipping GIF image
    // container.innerHTML = '<img src="URL_OF_YOUR_GIF" alt="Loading GIF">';
container.innerHTML = '<img src="../images/loader.gif" alt="Loading GIF">';


    // After a delay, remove the snipping GIF
    setTimeout(function() {
      container.innerHTML = '';
    }, 300); // Adjust the delay (in milliseconds) as needed
  });