function toggleMenu() {
    const nav = document.getElementById('mainNav');
    if (nav) nav.classList.toggle('show');
}

document.addEventListener('DOMContentLoaded', () => {
    const elements = document.querySelectorAll('.card, .animal-card, .request-card, .step-card, .admin-link, .chip');

    elements.forEach((el, i) => {
        el.classList.add('fade-up');
        el.style.transitionDelay = `${i * 35}ms`;
    });

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('show');
            }
        });
    }, {
        threshold: 0.12
    });

    elements.forEach(el => observer.observe(el));

    const hero = document.querySelector('.hero');
    if (hero) {
        hero.addEventListener('mousemove', (e) => {
            const x = (e.offsetX / hero.offsetWidth - 0.5) * 5;
            const y = (e.offsetY / hero.offsetHeight - 0.5) * 5;
            hero.style.transform = `rotateX(${-y}deg) rotateY(${x}deg)`;
        });

        hero.addEventListener('mouseleave', () => {
            hero.style.transform = 'rotateX(0deg) rotateY(0deg)';
        });
    }
});