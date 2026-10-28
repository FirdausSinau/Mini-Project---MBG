document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('[data-report-form]');

    if (!form) {
        return;
    }

    const schoolSelect = form.querySelector('#school_id');
    const distributionSelect = form.querySelector('#distribution_id');
    const status = form.querySelector('#distribution-status');
    const urlTemplate = form.dataset.distributionsUrlTemplate;
    const selectedDistribution = form.dataset.selectedDistribution || '';

    if (!schoolSelect || !distributionSelect || !status || !urlTemplate) {
        return;
    }

    const setStatus = (message, isError = false) => {
        status.textContent = message;
        status.classList.toggle('form-error', isError);
    };

    const resetDistribution = (message) => {
        distributionSelect.replaceChildren();

        const option = document.createElement('option');
        option.value = '';
        option.textContent = message;
        distributionSelect.appendChild(option);

        distributionSelect.disabled = true;
    };

    const fillDistributions = (distributions, selectedId) => {
        distributionSelect.replaceChildren();

        const placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.textContent = 'Pilih distribusi';
        distributionSelect.appendChild(placeholder);

        distributions.forEach((distribution) => {
            const option = document.createElement('option');
            option.value = String(distribution.id);
            option.textContent = distribution.label;

            if (String(distribution.id) === String(selectedId)) {
                option.selected = true;
            }

            distributionSelect.appendChild(option);
        });

        distributionSelect.disabled = false;
    };

    const loadDistributions = async (schoolId, selectedId = '') => {
        if (!schoolId) {
            resetDistribution('Pilih sekolah dulu');
            setStatus('');
            return;
        }

        resetDistribution('Memuat distribusi...');
        setStatus('Memuat distribusi...');

        try {
            const url = urlTemplate.replace('__SCHOOL__', encodeURIComponent(schoolId));
            const response = await fetch(url, {
                headers: {
                    'Accept': 'application/json',
                },
            });

            if (!response.ok) {
                throw new Error('Gagal memuat distribusi.');
            }

            const distributions = await response.json();

            if (distributions.length === 0) {
                resetDistribution('Belum ada distribusi untuk sekolah ini');
                setStatus('Belum ada distribusi untuk sekolah ini.');
                return;
            }

            fillDistributions(distributions, selectedId);
            setStatus('');
        } catch (error) {
            resetDistribution('Gagal memuat distribusi');
            setStatus(error.message, true);
        }
    };

    schoolSelect.addEventListener('change', () => {
        loadDistributions(schoolSelect.value);
    });

    if (schoolSelect.value) {
        loadDistributions(schoolSelect.value, selectedDistribution);
    } else {
        resetDistribution('Pilih sekolah dulu');
    }
});
