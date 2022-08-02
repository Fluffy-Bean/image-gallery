let alertClose = document.querySelectorAll(".alert");

function closeAlert(gone) {
  gone.closest(".alert").style.transform="translateY(-10rem) scale(0.8)";
  gone.closest(".alert").style.opacity="0";

  setTimeout(function(){
    gone.closest(".alert").style.display="none";
  }, 200);
};
