document.querySelector(".flyout-close").onclick = () =>{
  document.querySelector("html").style.overflow= "auto";

  document.querySelector(".flyout").style.bottom= "-50vh";
  document.querySelector(".flyout-dim").style.display= "none";
}

document.querySelector(".flyout-display").onclick = () =>{
  document.querySelector("html").style.overflow= "hidden";

  document.querySelector(".flyout").style.bottom= "-1rem";
  document.querySelector(".flyout-dim").style.display= "block";
}
