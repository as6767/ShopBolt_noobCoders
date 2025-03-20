document.addEventListener("DOMContentLoaded", () => {
    const loginframe = document.querySelector('.loginframe');
    const registerlink = document.querySelector('.registerlink');
    const loginlink = document.querySelector('.loginlink');
    const navbtnlogin = document.querySelector('.navbtnlogin');
    const closeicon = document.querySelector('.closeicon');

    if (!loginframe || !registerlink || !loginlink || !navbtnlogin || !closeicon) {
        console.error("One or more elements not found. Check class names.");
        return;
    }

    navbtnlogin.addEventListener('click', () => {
        loginframe.classList.add('show');  
        loginframe.classList.remove('active'); 
    });

    registerlink.addEventListener('click', () => {
        loginframe.classList.add('active');
    });

    loginlink.addEventListener('click', () => {
        loginframe.classList.remove('active');
    });

    closeicon.addEventListener('click', () => {
        loginframe.classList.remove('show');
    });
});
