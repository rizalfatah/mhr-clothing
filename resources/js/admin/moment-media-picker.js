function setupMomentMediaLibrary() {
    const dialog = document.querySelector('#moment-media-library');
    if (!dialog) return;
    const items = JSON.parse(document.querySelector('#moment-media-items').textContent);
    const grid = dialog.querySelector('[data-library-grid]');
    const search = dialog.querySelector('[data-library-search]');
    const upload = dialog.querySelector('[data-library-upload]');
    const status = dialog.querySelector('[data-library-status]');
    const error = dialog.querySelector('[data-library-error]');
    const uploadButton = dialog.querySelector('[data-library-upload-submit]');
    let origin = null;
    let pending = '';
    let uploading = false;
    let previousOverflow = '';

    function showError(message) {
        error.textContent = message;
        error.hidden = false;
    }

    function markSelection() {
        grid.querySelectorAll('[data-library-item]').forEach(button => {
            const selected = button.dataset.path === pending;
            button.setAttribute('aria-pressed', String(selected));
            button.className = 'min-w-0 overflow-hidden rounded-lg border text-left focus:outline-none focus:ring-2 focus:ring-blue-500 ' + (selected
                ? 'border-blue-500 bg-blue-50 ring-2 ring-blue-500 dark:border-blue-400 dark:bg-blue-500/10'
                : 'border-gray-200 bg-white hover:border-blue-400 dark:border-neutral-700 dark:bg-neutral-800');
            button.querySelector('[data-item-check]').hidden = !selected;
        });
    }

    function render() {
        const words = search.value.trim().toLocaleLowerCase().split(/\s+/).filter(Boolean);
        const matches = items.filter(item => words.every(word => `${item.alt} ${item.name}`.toLocaleLowerCase().includes(word)));
        grid.replaceChildren();
        matches.forEach(item => {
            const button = document.createElement('button');
            button.type = 'button';
            button.dataset.libraryItem = '';
            button.dataset.path = item.path;
            button.setAttribute('aria-label', `Pilih ${item.alt}`);
            const frame = document.createElement('span');
            frame.className = 'relative block bg-gray-50 dark:bg-neutral-900';
            const image = document.createElement('img');
            image.src = item.url;
            image.alt = item.alt;
            image.loading = 'lazy';
            image.className = 'h-32 w-full object-contain sm:h-40';
            const check = document.createElement('span');
            check.dataset.itemCheck = '';
            check.className = 'absolute right-2 top-2 rounded-full bg-blue-600 px-2 py-1 text-xs font-medium text-white';
            check.textContent = '✓ Dipilih';
            frame.append(image, check);
            const caption = document.createElement('span');
            caption.className = 'block truncate px-2 pt-2 text-xs font-medium';
            caption.textContent = item.alt;
            const filename = document.createElement('span');
            filename.className = 'block truncate px-2 pb-2 pt-1 text-xs text-gray-500 dark:text-neutral-400';
            filename.textContent = item.name;
            button.append(frame, caption, filename);
            button.addEventListener('click', () => {
                pending = item.path;
                markSelection();
            });
            grid.append(button);
        });
        dialog.querySelector('[data-library-count]').textContent = `${matches.length} dari ${items.length} foto`;
        dialog.querySelector('[data-library-empty]').hidden = matches.length > 0;
        markSelection();
    }

    function setUploading(value) {
        uploading = value;
        dialog.querySelectorAll('[data-library-cancel], [data-library-apply], [data-library-clear], [data-library-upload-toggle]').forEach(button => button.disabled = value);
        upload.querySelectorAll('input, button').forEach(input => input.disabled = value);
        uploadButton.textContent = value ? 'Mengunggah…' : 'Upload foto';
        upload.setAttribute('aria-busy', String(value));
    }

    document.addEventListener('click', event => {
        const trigger = event.target.closest('[data-media-open]');
        if (!trigger) return;
        origin = trigger.closest('[data-media-picker]');
        pending = origin.querySelector('[data-media-value]').value;
        search.value = '';
        status.textContent = '';
        error.hidden = true;
        upload.reset();
        upload.hidden = true;
        dialog.querySelector('[data-library-context]').textContent = origin.dataset.mediaLabel;
        render();
        previousOverflow = document.body.style.overflow;
        document.body.style.overflow = 'hidden';
        dialog.showModal();
        search.focus();
    });

    search.addEventListener('input', render);
    dialog.querySelectorAll('[data-library-cancel]').forEach(button => button.addEventListener('click', () => {
        if (!uploading) dialog.close();
    }));
    dialog.addEventListener('cancel', event => { if (uploading) event.preventDefault(); });
    dialog.addEventListener('close', () => {
        document.body.style.overflow = previousOverflow;
        origin?.querySelector('[data-media-open]').focus();
    });
    dialog.querySelector('[data-library-clear]').addEventListener('click', () => {
        pending = '';
        markSelection();
        status.textContent = 'Pilihan dikosongkan. Klik Gunakan foto untuk menerapkan.';
    });
    dialog.querySelector('[data-library-apply]').addEventListener('click', () => {
        if (uploading || !origin) return;
        const selected = items.find(item => item.path === pending);
        if (pending && !selected) {
            showError('Pilih foto yang tersedia terlebih dahulu.');
            return;
        }
        const input = origin.querySelector('[data-media-value]');
        const image = origin.querySelector('[data-media-preview-image]');
        input.value = pending;
        origin.querySelector('[data-media-preview]').hidden = !selected;
        origin.querySelector('[data-media-empty]').hidden = !!selected;
        origin.querySelector('[data-media-caption]').textContent = selected?.alt || 'Buka galeri foto';
        if (selected) {
            image.src = selected.url;
            image.alt = selected.alt;
        } else {
            image.removeAttribute('src');
            image.alt = '';
        }
        input.dispatchEvent(new Event('change', { bubbles: true }));
        dialog.close();
    });
    dialog.querySelectorAll('[data-library-upload-toggle]').forEach(button => button.addEventListener('click', () => {
        upload.hidden = !upload.hidden;
        if (!upload.hidden) upload.querySelector('[name="image"]').focus();
    }));
    upload.querySelector('[name="image"]').addEventListener('change', event => {
        const file = event.target.files[0];
        const description = upload.querySelector('[name="alt_text"]');
        if (file && !description.value.trim()) description.value = file.name.replace(/\.[^.]+$/, '').replace(/[-_]/g, ' ');
    });
    upload.addEventListener('submit', async event => {
        event.preventDefault();
        if (uploading) return;
        error.hidden = true;
        const file = upload.querySelector('[name="image"]').files[0];
        if (file && file.size > 5 * 1024 * 1024) {
            showError('Ukuran foto maksimal 5 MB.');
            return;
        }
        const form = new FormData(upload);
        setUploading(true);
        status.textContent = 'Sedang mengunggah foto…';
        try {
            const response = await fetch(upload.action, {
                method: 'POST', body: form, credentials: 'same-origin',
                headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            });
            if (!response.headers.get('content-type')?.includes('application/json')) {
                if (response.status === 413) throw new Error('Ukuran upload melebihi batas server. Pilih foto yang lebih kecil.');
                throw new Error('Upload belum berhasil. Periksa koneksi atau sesi login, lalu coba lagi.');
            }
            const data = await response.json();
            if (!response.ok) {
                const messages = Object.values(data.errors || {}).flat();
                throw new Error(messages.join(' ') || (response.status === 419 ? 'Sesi login kedaluwarsa. Muat ulang halaman sebelum mengunggah.' : 'Foto gagal diunggah. Silakan coba lagi.'));
            }
            if (!data.media?.path || !data.media?.url) throw new Error('Respons upload tidak dapat dibaca.');
            if (!items.some(item => item.path === data.media.path)) items.unshift(data.media);
            pending = data.media.path;
            search.value = '';
            upload.reset();
            upload.hidden = true;
            status.textContent = 'Foto berhasil diupload dan dipilih. Klik Gunakan foto untuk memasangnya.';
            render();
        } catch (failure) {
            status.textContent = '';
            showError(failure instanceof TypeError ? 'Koneksi terputus. Periksa koneksi lalu coba upload kembali.' : failure.message);
        } finally {
            setUploading(false);
        }
    });
}

if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', setupMomentMediaLibrary);
else setupMomentMediaLibrary();
