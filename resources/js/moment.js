const savedContent = JSON.parse(document.querySelector('#moment-content').textContent);
const menuToggle = document.querySelector('[data-menu-toggle]');
const navigation = document.querySelector('[data-navigation]');

function closeMenu() {
    navigation?.classList.remove('is-open');
    menuToggle?.setAttribute('aria-expanded', 'false');
    menuToggle?.setAttribute('aria-label', 'Buka menu');
    document.body.classList.remove('menu-is-open');
}

menuToggle?.addEventListener('click', () => {
    const open = menuToggle.getAttribute('aria-expanded') !== 'true';
    navigation.classList.toggle('is-open', open);
    menuToggle.setAttribute('aria-expanded', String(open));
    menuToggle.setAttribute('aria-label', open ? 'Tutup menu' : 'Buka menu');
    document.body.classList.toggle('menu-is-open', open);
});
document.addEventListener('click', (event) => {
    if (!event.target.closest('.site-header')) closeMenu();
});
navigation?.querySelectorAll('a').forEach((link) => link.addEventListener('click', closeMenu));
document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && menuToggle?.getAttribute('aria-expanded') === 'true') {
        closeMenu();
        menuToggle.focus();
    }
    if (event.key === 'Tab' && navigation?.classList.contains('is-open')) {
        const lastLink = navigation.querySelector('a:last-child');
        if (event.shiftKey && document.activeElement === menuToggle) {
            event.preventDefault();
            lastLink?.focus();
        } else if (!event.shiftKey && document.activeElement === lastLink) {
            event.preventDefault();
            menuToggle.focus();
        }
    }
});

const budgetInput = document.querySelector('[data-budget]');
const carousel = document.querySelector('[data-package-carousel]');
const cards = [...document.querySelectorAll('[data-package-card]')];
const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
function centerCard(card, smooth = true) {
    if (!carousel || !card || window.matchMedia('(min-width: 900px)').matches) return;
    carousel.scrollTo({ left: card.offsetLeft - carousel.offsetLeft - (carousel.clientWidth - card.offsetWidth) / 2, behavior: smooth && !reducedMotion ? 'smooth' : 'instant' });
}
function updateBudget() {
    const budget = Number(budgetInput.value);
    const formatted = new Intl.NumberFormat('id-ID').format(budget);
    document.querySelector('[data-budget-output]').textContent = formatted;
    budgetInput.setAttribute('aria-valuetext', `${formatted} rupiah`);
    const affordable = cards.filter((card) => Number(card.dataset.price) <= budget);
    const recommendation = affordable.reduce((best, card) => !best || Number(card.dataset.price) > Number(best.dataset.price) ? card : best, null);
    cards.forEach((card) => {
        const recommended = card === recommendation;
        card.classList.toggle('is-recommended', recommended);
        card.querySelector('[data-recommendation-tag]').hidden = !recommended;
        if (recommended) card.dataset.recommended = 'true';
        else delete card.dataset.recommended;
    });
    document.querySelector('[data-budget-status]').textContent = recommendation
        ? savedContent.copy.budget_match.replace('{package_name}',recommendation.dataset.name)
        : savedContent.copy.minimum_budget.replace('{price}',new Intl.NumberFormat('id-ID').format(Math.min(...cards.map(card => Number(card.dataset.price)))));
    if (recommendation) centerCard(recommendation);
}
budgetInput?.addEventListener('input', updateBudget);
if (carousel) {
    updateBudget();
    requestAnimationFrame(() => centerCard(cards.find((card) => card.dataset.recommended), false));
    const moveCarousel = (direction) => {
        const center = carousel.scrollLeft + carousel.clientWidth / 2;
        const current = cards.reduce((best, card, index) => Math.abs(card.offsetLeft - carousel.offsetLeft + card.offsetWidth / 2 - center) < Math.abs(cards[best].offsetLeft - carousel.offsetLeft + cards[best].offsetWidth / 2 - center) ? index : best, 0);
        centerCard(cards[Math.max(0, Math.min(cards.length - 1, current + direction))]);
    };
    document.querySelector('[data-carousel-previous]')?.addEventListener('click', () => moveCarousel(-1));
    document.querySelector('[data-carousel-next]')?.addEventListener('click', () => moveCarousel(1));
    carousel.addEventListener('keydown', (event) => {
        if (event.key === 'ArrowRight' || event.key === 'ArrowLeft') {
            event.preventDefault();
            moveCarousel(event.key === 'ArrowRight' ? 1 : -1);
        }
    });
}

const photoDialog = document.querySelector('#photo-dialog');
document.querySelectorAll('[data-lightbox]').forEach((button) => {
    button.addEventListener('click', () => {
        const source = button.querySelector('img');
        const stage = photoDialog.querySelector('[data-lightbox-stage]');
        if (button.classList.contains('preset-preview')) {
            // Preserve the preset colour and design composition in its preview.
            const composition = document.createElement('div');
            composition.className = `${button.className} lightbox-composition`;
            [...button.children].filter((child) => !child.matches('.preview-label, .preview-expand'))
                .forEach((child) => composition.appendChild(child.cloneNode(true)));
            stage.replaceChildren(composition);
        } else {
            const target = document.createElement('img');
            target.src = source.currentSrc || source.src;
            target.alt = source.alt;
            target.dataset.lightboxImage = '';
            stage.replaceChildren(target);
        }
        photoDialog.showModal();
        document.body.classList.add('dialog-is-open');
    });
});
document.querySelectorAll('[data-gallery]').forEach((gallery) => {
    const thumbnails = [...gallery.querySelectorAll('[data-gallery-thumbnail]')];
    thumbnails.forEach((thumbnail) => thumbnail.addEventListener('click', () => {
        gallery.querySelector('[data-gallery-image]').src = thumbnail.dataset.src;
        gallery.querySelector('[data-gallery-image]').style.objectPosition = thumbnail.dataset.position;
        gallery.querySelector('[data-gallery-counter]').textContent = `${String(Number(thumbnail.dataset.index) + 1).padStart(2, '0')} / ${String(thumbnails.length).padStart(2, '0')}`;
        thumbnails.forEach((item) => {
            item.classList.toggle('is-active', item === thumbnail);
            item.setAttribute('aria-pressed', String(item === thumbnail));
        });
    }));
});

document.querySelectorAll('[data-filter]').forEach((button) => {
    button.addEventListener('click', () => {
        document.querySelectorAll('[data-filter]').forEach((item) => {
            item.classList.toggle('is-active', item === button);
            item.setAttribute('aria-pressed', String(item === button));
        });
        const items = [...document.querySelectorAll('[data-preset-item]')];
        items.forEach((item) => { item.hidden = button.dataset.filter !== 'all' && item.dataset.type !== button.dataset.filter; });
        document.querySelector('[data-filter-status]').textContent = savedContent.copy.filter_count.replace('{count}',items.filter((item) => !item.hidden).length);
    });
});

const infoDialog = document.querySelector('#info-dialog');
const information = {
    terms: { title: savedContent.copy.text_68, text: savedContent.terms },
    privacy: { title: savedContent.copy.text_69, text: savedContent.privacy },
};
document.querySelectorAll('[data-info]').forEach((button) => button.addEventListener('click', () => {
    const item = information[button.dataset.info];
    infoDialog.querySelector('#info-dialog-title').textContent = item.title;
    const paragraph = document.createElement('p');
    paragraph.textContent = item.text;
    const link = document.createElement('a');
    link.href = savedContent.whatsapp;
    link.textContent = 'Hubungi Sepulang Moment ↗';
    link.target = '_blank';
    link.rel = 'noopener';
    infoDialog.querySelector('[data-info-content]').replaceChildren(paragraph, link);
    infoDialog.showModal();
    document.body.classList.add('dialog-is-open');
}));
document.querySelectorAll('dialog').forEach((dialog) => {
    dialog.addEventListener('close', () => document.body.classList.remove('dialog-is-open'));
    dialog.addEventListener('click', (event) => { if (event.target === dialog) dialog.close(); });
});
