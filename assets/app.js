/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import "./styles/app.scss";

// start the Stimulus application
import "./bootstrap";



const btnCopy = document.querySelector(".btn-copy");
const passwordText = document.querySelector(".password-text");

btnCopy.addEventListener("click", () => {
  navigator.clipboard.writeText(passwordText.innerText);
  btnCopy.innerText = "Copied !";
});

const btnRefresh = document.querySelector(".btn-refresh");

btnRefresh.addEventListener("click", () => {
  location.reload();
});

