// Бургер-меню
const burger = document.getElementById('burger');
const nav = document.getElementById('mainNav');
if (burger && nav) {
    burger.addEventListener('click', () => {
        nav.classList.toggle('open');
    });
}

// Слайдер
(function () {
    const slider = document.querySelector('.slider');
    if (!slider) return;

    const slides = slider.querySelectorAll('.slide');
    const prevBtn = document.querySelector('.slider-btn.prev');
    const nextBtn = document.querySelector('.slider-btn.next');
    const dotsContainer = document.querySelector('.slider-dots');
    let current = 0;
    let timer;

    if (dotsContainer) {
        slides.forEach((_, i) => {
            const dot = document.createElement('span');
            if (i === 0) dot.classList.add('active');
            dot.addEventListener('click', () => goTo(i));
            dotsContainer.appendChild(dot);
        });
    }

    function goTo(index) {
        current = (index + slides.length) % slides.length;
        slider.style.transform = `translateX(-${current * 100}%)`;
        if (dotsContainer) {
            dotsContainer.querySelectorAll('span').forEach((d, i) => {
                d.classList.toggle('active', i === current);
            });
        }
        resetTimer();
    }

    function next() { goTo(current + 1); }
    function prev() { goTo(current - 1); }

    if (nextBtn) nextBtn.addEventListener('click', next);
    if (prevBtn) prevBtn.addEventListener('click', prev);

    function resetTimer() {
        clearInterval(timer);
        timer = setInterval(next, 3000);
    }
    resetTimer();
})();

// Рейтинг звёздами
document.querySelectorAll('.stars-input').forEach(container => {
    const stars = container.querySelectorAll('span');
    const input = container.parentElement.querySelector('input[name="rating"]');
    stars.forEach((star, idx) => {
        star.addEventListener('click', () => {
            const val = idx + 1;
            if (input) input.value = val;
            stars.forEach((s, i) => s.classList.toggle('active', i < val));
        });
    });
});
