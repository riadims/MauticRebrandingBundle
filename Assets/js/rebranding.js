document.addEventListener('DOMContentLoaded', function() {
    loadBrandingConfig();
});

function loadBrandingConfig() {
    fetch('/plugins/RebrandingBundle/Config/config.json?t=' + Date.now())
        .then(response => {
            if (!response.ok) throw new Error('Config not found');
            return response.json();
        })
        .then(config => {
            applyBranding(config);
        })
        .catch(error => {
            console.log('No rebranding config found, using default styles.');
            removeBranding();
        });
}

function applyBranding(config) {
    removeBranding();
    
    hideDefaultBranding();
    
    applyCustomBranding(config);

    applyLoginBranding(config);
    
    applyColorVariables(config);
    
    document.body.classList.add('rebranding-active');
}

function removeBranding() {
    document.querySelectorAll('.rebranding-logo, .rebranding-text, .rebranding-login-logo').forEach(el => {
        if (el.parentNode) {
            el.parentNode.removeChild(el);
        }
    });
    
    const colorStyle = document.getElementById('rebranding-colors');
    if (colorStyle && colorStyle.parentNode) {
        colorStyle.parentNode.removeChild(colorStyle);
    }
    
    document.body.classList.remove('rebranding-active');
    
    document.querySelectorAll('.mautic-logo-figure, .mautic-logo-text, .mautic-logo').forEach(el => {
        el.style.display = '';
    });
}

function hideDefaultBranding() {
    document.querySelectorAll('.mautic-logo-figure, .mautic-logo-text').forEach(el => {
        el.style.display = 'none';
    });
}

function applyCustomBranding(config) {
    const mauticBrand = document.querySelector('.mautic-brand');
    if (mauticBrand) {
        if (config.logo) {
            const customLogo = document.createElement('img');
            customLogo.src = config.logo + '?t=' + Date.now();
            customLogo.alt = config.brand_name || 'Logo';
            customLogo.className = 'rebranding-logo';
            customLogo.style.cssText = 'height:30px;margin-right:5px;';
            mauticBrand.prepend(customLogo);
        }

        if (config.brand_name) {
            const customText = document.createElement('span');
            customText.textContent = config.brand_name;
            customText.className = 'rebranding-text';
            customText.style.cssText = `
                font-family: 'Open Sans', sans-serif;
                font-size: 22px;
                font-weight: bold;
                color: ${config.primary_color || '#4E5E9E'};
            `;
            mauticBrand.appendChild(customText);
        }
    }
}

function applyLoginBranding(config) {
    const loginContainer = document.querySelector('.mautic-logo');
    if (!loginContainer) return;

    if (config.logo) {
        const customLoginLogo = document.createElement('img');
        customLoginLogo.src = config.logo + '?t=' + Date.now();
        customLoginLogo.alt = config.brand_name || 'Logo';
        customLoginLogo.className = 'rebranding-active';
        customLoginLogo.style.cssText = 'height:150px; border-radius:50%;';
        loginContainer.prepend(customLoginLogo);
    }
}

function applyColorVariables(config) {
    if (!config.primary_color) return;
    
    const oldStyle = document.getElementById('rebranding-colors');
    if (oldStyle) oldStyle.remove();
    
    const style = document.createElement('style');
    style.id = 'rebranding-colors';
    style.textContent = `
        :root {
            --primary-color: ${config.primary_color};
            --secondary-color: color-mix(in srgb, var(--primary-color), black 10%);
        }

        body.rebranding-active .btn-primary,
        body.rebranding-active .label-primary {
            background-color: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
        }
        
        body.rebranding-active .btn-ghost,
        body.rebranding-active .btn-ghost i {
            color: var(--primary-color) !important;
            border-color: #ffffff00 !important;
        }

        body.rebranding-active .btn-save,
        body.rebranding-active .btn-save i,
        body.rebranding-active .btn-cancel,
        body.rebranding-active .btn-cancel i,
        #user_buttons_save_toolbar {
            color: var(--primary-color) !important;
            background-color: #ffffff00 !important;
            border-color: var(--primary-color) !important;
        }

        .xdsoft_current {
            color: #ffffff !important;
            background-color: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
        }

        #user_buttons_cancel_toolbar,
        #user_buttons_cancel_toolbar i,
         {
            background-color: var(--primary-color) !important;
            color: white !important;
        }

        .ri-arrow-down-s-line{
        background-color: #ffffff00 !important;
        color:white !important
        }

        #user_buttons_cancel_toolbar:hover,
        #user_buttons_cancel_toolbar:hover i {
            background-color: var(--primary-color) !important;
            color: white !important;
            transition: all 0.5s ease;
            filter: brightness(90%);
        }

        #config_buttons_apply_toolbar:hover,
        body.rebranding-active .btn-nospin:hover,
        body.rebranding-active .btn-save:hover,
        body.rebranding-active .btn-save:hover .ri-save-line,
        body.rebranding-active .btn-cancel:hover,
        body.rebranding-active .btn-cancel:hover .ri-close-line,
        #new:hover,
        #user_buttons_save_toolbar:hover,
        #user_buttons_save_toolbar:hover i {
            background-color: var(--secondary-color) !important;
            color: white !important;
            border-color: var(--secondary-color) !important;
            transition: all 0.1s ease;
        }

        body.rebranding-active .btn-ghost:hover {
            color: var(--secondary-color) !important;
            background-color: #ffffff00 !important;
            border-color: #ffffff00 !important;
        }

        .tab-pane .panel-heading {
            color: white !important;
            background-color: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
        }

        .active .pull-left,
        .open .pull-left,
        .table-hover a,
        .login-form a,
        body.rebranding-active .text-primary {
            color: var(--primary-color) !important;
        }
    `;
    document.head.appendChild(style);
}


document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        Chart.helpers.each(Chart.instances, (chart) => {
            chart.config.data.datasets.forEach(dataset => {
                dataset.borderColor = '#000000';
                dataset.backgroundColor = 'rgba(0,0,0,0.1)';
            });

            chart.options.tooltips = {
                backgroundColor: '#000000',
                titleFontColor: '#ffffff',
                bodyFontColor: '#ffffff',
                caretSize: 0,
                borderColor: '#ffffff',
                borderWidth: 1,
                displayColors: false
            };

            chart.update();
        });

        const style = document.createElement('style');
        style.textContent = `
            .chartjs-tooltip {
                background: #000 !important;
                border: 1px solid #fff !important;
                color: #fff !important;
                opacity: 100 !important;
            }
            
            .chartjs-tooltip-header,
            .chartjs-tooltip-body-item span {
                color: #fff !important;
            }
        `;
        document.head.appendChild(style);
    }, 250); //If timeout is shorter the charts wont have time to load and therefore wont change
});