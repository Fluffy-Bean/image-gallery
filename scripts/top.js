button = document.getElementById("back-to-top");

window.onscroll = function() {scrollFunction()};

function scrollFunction() {
  if (document.body.scrollTop > 300 || document.documentElement.scrollTop > 20) {
    button.style.right = "1rem";
  } else {
    button.style.right = "-2.5rem";
  }
}
