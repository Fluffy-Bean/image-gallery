$(document).ready(function() {
  var flyoutRoot = document.getElementById("#flyoutRoot");
  var flyoutDim = document.getElementById("#flyoutDim");

  var flyoutHeader = document.getElementById("#flyoutHeader");
  var flyoutDescription = document.getElementById("#flyoutDescription");
  var flyoutActionbox = document.getElementById("#flyoutActionbox");
});

function flyoutShow(header, description, actionbox) {
  // Hide overflow
  document.querySelector("html").style.overflow = "hidden";
  // Checking if actionbox is set
  if (typeof actionbox === 'undefined') {
    flyoutActionbox.style.display = "none";
  } else if (actionbox == "") {
    flyoutActionbox.style.display = "none";
  } else {
    flyoutActionbox.style.display = "block";
  }

  // Set information in flyout
  flyoutHeader.textContent = header;
  flyoutDescription.textContent = description;
  $(flyoutActionbox).html(actionbox);

  // Show the flyout
  flyoutRoot.style.display = "flex";
  // Show the dim
  flyoutDim.style.display = "block";
  setTimeout(function(){
    // Show the flyout
    flyoutRoot.style.transform = "translateX(-50%) scale(1)";
    flyoutRoot.style.bottom = "1rem";
    // Show the dim
    flyoutDim.style.opacity = "1";
  }, 1);
};

function flyoutClose() {
  // Show overflow
  document.querySelector("html").style.overflow = "auto";
  // Hide the flyout
  flyoutRoot.style.transform = "translateX(-50%) scale(0.5)";
  flyoutRoot.style.bottom = "-20rem";
  // Hide the dim
  flyoutDim.style.opacity = "0";
  setTimeout(function(){
    // Hide the flyout
    flyoutRoot.style.display = "none";
    // Hide the dim
    flyoutDim.style.display = "none";
  }, 500);
};
