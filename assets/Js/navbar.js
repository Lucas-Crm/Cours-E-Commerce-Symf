console.log("navbar.js");

let btnNav = document.querySelector('#nav-admin');
let navAdmin = document.querySelector('#nav-admin-content');

btnNav.addEventListener('click', switchNav);

function switchNav() {
    if(navAdmin.classList.contains('nav-hidden')){
        navAdmin.classList.replace('nav-hidden', 'nav-show');
    } else {
        navAdmin.classList.replace('nav-show', 'nav-hidden');
    }
}






