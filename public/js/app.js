const mobileMenu = document.querySelector('#mobile-menu');
const mobileNav = document.querySelector('.mobile-nav');
const main = document.querySelector('main');
const overlay = document.querySelector('.menu-overlay');
const cookies = document.querySelector('.cookies-container');
const cookiesButton = document.querySelector('#cookies-button')
let menuOpen = 'closed';

let alerted = localStorage.getItem('alerted') || '';

function openNav()
{
    mobileNav.style.transform = "translateX(-54px)";
    overlay.style.display = "block";
    setTimeout(() => {
        overlay.style.backgroundColor = "rgba(0, 0, 0, 0.9)";
    }, 100);
    menuOpen = 'open';
    
}

function closeNav()
{
    mobileNav.style.transform = "translateX(-105vw)";
    overlay.style.backgroundColor = "rgba(0, 0, 0, 0)";
    setTimeout(() => {
        overlay.style.display = "none";
    }, 500);
    menuOpen = 'closed';
}

function mobileMenuFunction()
{
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

//todo: make this work
if (alerted != 'yes') {
    cookies.style.display = 'block';
}

cookiesButton.addEventListener('click', function () {
    cookies.style.display = 'none';
    localStorage.setItem('alerted','yes')
});