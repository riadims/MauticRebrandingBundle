document.addEventListener('DOMContentLoaded', function() {
    cleanUpBranding();
    
    fetch('/plugins/RebrandingBundle/Config/config.json?t=' + Date.now())
        .then(response => {
            if (!response.ok) throw new Error('Config not found');
            return response.json();
        })
        .then(config => {
            applyHeaderBranding(config);
            
            applyLoginBranding(config);
        })
        .catch(error => {
            console.log('No rebranding config found, using default branding.');
            restoreDefaultBranding();
        });

        document.getElementById('revert-to-default')?.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (confirm('Are you sure you want to revert to default Mautic branding?')) {
                window.location.href = '/s/rebranding/revert';
            }
        });
});

function cleanUpBranding() {
    document.querySelectorAll('.rebranding-logo, .rebranding-text, .rebranding-login-logo').forEach(el => el.remove());
    
    const defaultLogo = document.querySelector('.mautic-logo-figure');
    const defaultText = document.querySelector('.mautic-logo-text');
    if (defaultLogo) defaultLogo.style.display = '';
    if (defaultText) defaultText.style.display = '';
}

function applyHeaderBranding(config) {
    const mauticBrand = document.querySelector('.mautic-brand');
    if (!mauticBrand) return;

    const defaultLogo = mauticBrand.querySelector('.mautic-logo-figure');
    const defaultText = mauticBrand.querySelector('.mautic-logo-text');
    if (defaultLogo) defaultLogo.style.display = 'none';
    if (defaultText) defaultText.style.display = 'none';

    if (config.logo) {
        const customLogo = document.createElement('img');
        customLogo.src = config.logo + '?t=' + Date.now();
        customLogo.alt = config.brand_name || 'Logo';
        customLogo.className = 'rebranding-logo';
        customLogo.style.cssText = 'height:30px; margin-right:5px;';
        mauticBrand.prepend(customLogo);
    }

    if (config.brand_name) {
        const customText = document.createElement('span');
        customText.textContent = config.brand_name;
        customText.className = 'rebranding-text text-primary';
        customText.style.cssText = `
            font-family: 'Open Sans', sans-serif;
            font-size: 22px;
            font-weight: bold;
            display: inline-block;
            vertical-align: middle;
        `;
        mauticBrand.appendChild(customText);
    }
}

function applyLoginBranding(config) {
    const loginContainer = document.querySelector('.mautic-logo');
    if (!loginContainer) return;

    const defaultLoginLogo = loginContainer.querySelector('.mautic-logo-figure');
    if (defaultLoginLogo) defaultLoginLogo.style.display = 'none';

    if (config.logo) {
        const customLoginLogo = document.createElement('img');
        customLoginLogo.src = config.logo + '?t=' + Date.now();
        customLoginLogo.alt = config.brand_name || 'Logo';
        customLoginLogo.className = 'rebranding-login-logo';
        customLoginLogo.style.cssText = 'height:150px; border-radius:50%;';
        loginContainer.prepend(customLoginLogo);
    }
}
