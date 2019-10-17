const mobileMenu = document.querySelector('#mobile-menu');
const mobileNav = document.querySelector('.mobile-nav');
let menuOpen = 'closed';


mobileMenu.addEventListener('click', () => {
    switch (menuOpen) {
        case 'open':
            mobileNav.style.transform = "translateX(-105vw)";
            menuOpen = 'closed';
            break;
        default:
            mobileNav.style.display = "flex";
            mobileNav.style.transform = "translateX(-25px)";
            menuOpen = 'open';
            break;
    }
});
//it works so far