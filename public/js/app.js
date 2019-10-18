const mobileMenu = document.querySelector('#mobile-menu');
const mobileNav = document.querySelector('.mobile-nav');
const body = document.querySelector('body');
let menuOpen = 'closed';

setTimeout(() => mobileNav.style.display = "flex", 1000);
// fix the body listener
function openNav() {
    mobileNav.style.transform = "translateX(-54px)";
    menuOpen = 'open';
    // setTimeout(() => { 
    //     body.addEventListener('click', () => {
    //     closeNav();
    //     }) 
    // }, 500); 
}

function closeNav() {
    mobileNav.style.transform = "translateX(-105vw)";
    menuOpen = 'closed';
    // body.removeEventListener('click', () => {
    //     closeNav(), true;
    // });
    // mobileMenuFunction();
}

function mobileMenuFunction() {
    mobileMenu.addEventListener('click', () => {
        switch (menuOpen) {
            case 'open':
                closeNav();
                break;
            default:
                openNav();
                break;
        }
    });
}

window.addEventListener("resize", () => {
    closeNav() 
} );

mobileMenuFunction();