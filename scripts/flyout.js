console.log(" . . /|/| . . . . . . . \n .. /0 0 \\ . . . . . .. \n (III% . \\________, . . \n .. .\\_, .%###%/ \\'\\,.. \n . . . .||#####| |'\\ \\. \n .. . . ||. . .|/. .\\V. \n . . . .|| . . || . . . \n .. . . ||. . .||. . .. \n . . . .|| . . || . . . \n .. . . ||. . .||. . .. \n . . . .|| . . || . . . \n .. . . ||. . .||. . .. \n . . . .|| . . || . . . \n .. . . ||. . .||. . .. \n . . . .|| . . || . . . \n .. . . ||. . .||. . .. \n . . . .|| . . || . . . \n .. . . ||. . .||. . .. \n . . . .|| . . || . . . \n .. . . ||. . .||. . .. \n . . . .|| . . || . . . \n .. . . ||. . .||. . .. \n . . . cc/ . .cc/ . . .");

let show = document.querySelectorAll(".flyout-display");
let hide = document.querySelector(".flyout-close");

show.forEach(function(){
  document.querySelector(".flyout").style.transform= "translateX(-50%) scale(1)";
  document.querySelector(".flyout").style.bottom= "-1rem";
  document.querySelector(".flyout-dim").style.display= "block";
});


hide.addEventListener("click", function(){
  document.querySelector(".flyout").style.transform= "translateX(-50%) scale(0.8)";
  document.querySelector(".flyout").style.bottom= "-20rem";
  document.querySelector(".flyout-dim").style.display= "none";
});
