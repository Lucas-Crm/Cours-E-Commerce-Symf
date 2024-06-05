console.log("navbar.js");

let btnNav = document.querySelector('#nav-admin');
let navAdmin = document.querySelector('#nav-admin-content');
let isNavOpen = false;

btnNav.addEventListener('click', switchNav);

function switchNav() {
    if(isNavOpen){
        navAdmin.classList.replace('nav-hidden', 'nav-show');
    } else {
        navAdmin.classList.replace('nav-show', 'nav-hidden');
    }
    isNavOpen = !isNavOpen;
}






