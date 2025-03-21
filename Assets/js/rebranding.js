document.addEventListener('DOMContentLoaded', function () {
    fetch('/plugins/RebrandingBundle/Config/config.json')
        .then(response => response.json())
        .then(config => {
            const mauticBrand = document.querySelector('.mautic-brand');
            if (mauticBrand) {
                const existingLogo = mauticBrand.querySelector('.mautic-logo-figure');
                const existingText = mauticBrand.querySelector('.mautic-logo-text');
                if (existingLogo) existingLogo.remove();
                if (existingText) existingText.remove();

                if (config.logo) {
                    const customLogo = document.createElement('img');
                    customLogo.src = config.logo;
                    customLogo.alt = config.brand_name || 'Logo';
                    customLogo.style.height = '30px';
                    customLogo.style.marginRight = '5px';
                    mauticBrand.prepend(customLogo);
                }

                if (config.brand_name) {
                    const customText = document.createElement('span');
                    customText.textContent = config.brand_name;
                    customText.style.fontFamily = 'Open Sans, sans-serif';
                    customText.style.fontSize = '22px';
                    customText.style.fontWeight = 'bold';
                    customText.style.color = config.primary_color || '#4E5E9E';
                    mauticBrand.appendChild(customText);
                }

                const loginLogoContainer = document.querySelector('.mautic-logo');
                if (loginLogoContainer) {
                    const existingLoginLogo = loginLogoContainer.querySelector('.mautic-logo-figure');
                    if (existingLoginLogo) existingLoginLogo.remove();

                    if (config.logo) {
                        const customLoginLogo = document.createElement('img');
                        customLoginLogo.src = config.logo;
                        customLoginLogo.alt = config.brand_name || 'Logo';
                        loginLogoContainer.prepend(customLoginLogo);
                    }
                }
            }

            if (config.primary_color) {
                document.documentElement.style.setProperty('--primary-color', config.primary_color);
                document.documentElement.style.setProperty('--primary-color-hover', config.primary_color + 'CC');

                const primaryButtons = document.querySelectorAll('.btn-primary');
                primaryButtons.forEach(button => {
                    button.style.backgroundColor = config.primary_color;
                    button.style.borderColor = config.primary_color;
                });

                const primaryText = document.querySelectorAll('.text-primary');
                primaryText.forEach(text => {
                    text.style.color = config.primary_color;
                });
            }
        })
        .catch(error => console.error('Error loading rebranding config:', error));

    const revertButton = document.getElementById('revert-to-default');
    if (revertButton) {
        revertButton.addEventListener('click', function (event) {
            event.preventDefault();
            if (confirm('Are you sure you want to revert to the default Mautic UI settings?')) {
                fetch('/rebranding/revert', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            alert('Reverted to default settings successfully.');
                            window.location.href = '/s/rebranding';
                        } else {
                            alert('Failed to revert to default settings: ' + (data.message || 'Unknown error'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while reverting to default settings.');
                    });
            }
        });
    } else {
        console.error('Revert button not found.');
    }
});