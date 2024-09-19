export default (
    tenantDropdownSelector: string,
    parentDropdownSelector: string,
) => {
    const $tenantIdDropdown: HTMLSelectElement = document.querySelector(tenantDropdownSelector);
    const $parentDropdown: HTMLSelectElement = document.querySelector(parentDropdownSelector);

    let currentValue = $tenantIdDropdown.value;

    $tenantIdDropdown.addEventListener('change', function () {
        if($tenantIdDropdown.value === currentValue) {
            return;
        }

        const url = new URL(window.location.href);

        currentValue = $tenantIdDropdown.value;
        url.searchParams.set('tenant', currentValue);

        $parentDropdown.disabled = true;

        fetch(url)
            .then(response => response.text())
            .then(text => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(text, 'text/html');
                const $newDropdown = doc.querySelector(parentDropdownSelector);

                $parentDropdown.innerHTML = $newDropdown.innerHTML || '';

                // Override the default value with the correct tenant absolute URL.
                $parentDropdown.options[0].dataset.value = $tenantIdDropdown.options[$tenantIdDropdown.selectedIndex].dataset.value;

                $parentDropdown.disabled = false;
                $parentDropdown.dispatchEvent(new Event('change'));
            });
    });

    // Trigger the tenant dropdown change event once to apply the initial parent dropdown values.
    $tenantIdDropdown.dispatchEvent(new Event('change'));
}