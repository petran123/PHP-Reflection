const mobileMenu = document.querySelector('#mobile-menu');
const mobileNav = document.querySelector('.mobile-nav');
const main = document.querySelector('main');
const overlay = document.querySelector('.menu-overlay');
let menuOpen = 'closed';


function openNav() {
    mobileNav.style.transform = "translateX(-54px)";
    overlay.style.display = "block";
    setTimeout(() => {
        overlay.style.backgroundColor = "rgba(0, 0, 0, 0.9)";
    }, 100);
    menuOpen = 'open';
    
}

function closeNav() {
    mobileNav.style.transform = "translateX(-105vw)";
    overlay.style.backgroundColor = "rgba(0, 0, 0, 0)";
    setTimeout(() => {
        overlay.style.display = "none";
    }, 500);
    menuOpen = 'closed';
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
});

//switch to main if it doesn't work
main.addEventListener('click', () => {
    if (menuOpen == 'open') {
        closeNav();
    }
});

mobileMenuFunction();