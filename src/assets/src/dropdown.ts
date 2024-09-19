export default (
    tenantDropdownSelector: string,
    parentDropdownSelector: string,
) => {
    const $tenantIdDropdown: HTMLSelectElement = document.querySelector(tenantDropdownSelector);
    const $parentDropdown: HTMLSelectElement = document.querySelector(parentDropdownSelector);

    const updateDefaultParentDropdownValue = () => {
        $parentDropdown.options[0].dataset.value = $tenantIdDropdown.options[$tenantIdDropdown.selectedIndex].dataset.value;
        $parentDropdown.dispatchEvent(new Event('change'));
    }

    $tenantIdDropdown.addEventListener('change', function () {
        const url = new URL(window.location.href);
        url.searchParams.set('tenant', $tenantIdDropdown.value);

        $parentDropdown.disabled = true;

        fetch(url)
            .then(response => response.text())
            .then(text => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(text, 'text/html');
                const $newDropdown = doc.querySelector(parentDropdownSelector);

                $parentDropdown.innerHTML = $newDropdown.innerHTML || '';
                $parentDropdown.disabled = false;

                updateDefaultParentDropdownValue();
            });
    });

    updateDefaultParentDropdownValue();
}