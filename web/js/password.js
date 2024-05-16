'use strict';
function Password(element, options) {
    const DEFAULTS = {
        append: 'append',
        iconPrefix: 'fa',
        iconShow: 'fa-eye',
        iconHide: 'fa-eye-slash',
        tooltip: 'Show/Hide password',
        version: '2.0',
        clientValidation: false,
        debug: false,
        appendImg: '',
        appendImgHide: '',
        templates: {
            wrap: '<div class="input-group"></div>',
            icon: '<div class="input-group-{append}"><span class="input-group-text" title="{tooltip}"><i class="{iconPrefix} {iconShow}" aria-hidden="true"></i></span></div>',
        }
    };

    this.initConfig = () => {
        this.$input = $(element);
        this.input = $(element).get(0);
        this.form = this.input.closest('form');
        this.options = $.extend(DEFAULTS, options);
        this.isVisible = false;
        this.alreadySubmited = false;
    };

    this.toggle = () => {
        let preEvent = this.isVisible ? 'hide' : 'show';
        let postEvent = this.isVisible ? 'hidden' : 'shown';

        this.$input.trigger(preEvent + '.bs.password');

        if(!this.isVisible) {
            this.$input.attr('type', 'text');
            this.$icon.find('i')
                .removeClass(DEFAULTS.iconShow)
                .addClass(DEFAULTS.iconHide);
            // Support for Font Awesome v5
            this.$icon.find('svg')
                .removeClass(DEFAULTS.iconShow)
                .addClass(DEFAULTS.iconHide);

            this.$icon.find('img')
                .attr('src', DEFAULTS.appendImgHide)

        } else {
            this.$input.attr('type', 'password');
            this.$icon.find('i')
                .removeClass(DEFAULTS.iconHide)
                .addClass(DEFAULTS.iconShow);
            // Support for Font Awesome v5
            this.$icon.find('svg')
                .removeClass(DEFAULTS.iconHide)
                .addClass(DEFAULTS.iconShow);
            this.$icon.find('img')
                .attr('src', DEFAULTS.appendImg);
        }

        this.$input.trigger(postEvent + '.bs.password');

        this.isVisible = !this.isVisible;
    };

    this.formSubmitHandler = () => {
        this.alreadySubmited = true;
        const isPasswordValid = this.validate();
        if (!isPasswordValid) {
            this.input.setCustomValidity(this.options.messages.validationError);
            this.input.classList.add('is-invalid');
        } else {
            this.input.setCustomValidity('');
            this.input.classList.remove('is-invalid');
        }
    };

    this.renderAlerts = () => {
        this.alertBadges = {};

        const validationMessages = this.options.messages.validationCases;
        const validationTip = document.createElement('div');
        validationTip.className = 'text-muted mb-1';
        validationTip.innerText = this.options.messages.validationTip;
        const alertsWrapper = document.createElement('div');
        alertsWrapper.className = 'badge-select badge-select-sm py-1';
        const alerts = document.createElement('div');
        alerts.className = 'badge-select-content';

        for (let key in validationMessages) {
            const alert = document.createElement('div');
            alert.className = 'badge-select-item';
            alert.style.pointerEvents = 'none';
            alert.style.whiteSpace = 'nowrap';
            alert.innerText = validationMessages[key];
            alert.dataset.value = key;
            alerts.append(alert);

            this.alertBadges[key] = alert;
        }

        alertsWrapper.append(validationTip, alerts);
        $(alertsWrapper).insertAfter(this.$input.closest('.input-group'));
    };

    this.validate = () => {
        const value = this.input.value;
        const setBadgeSuccess = (badge) => {
            badge.classList.remove('badge-select-item--danger');
            badge.classList.add('badge-select-item--success');
        };
        const setBadgeError = (badge) => {
            badge.classList.remove('badge-select-item--success');

            if (this.alreadySubmited) {
                badge.classList.add('badge-select-item--danger');
            }
        };
        let hasErrors = false;

        // Uppercase validation
        if ('uppercase' in this.alertBadges) {
            if ((/[A-Z]/.test(value))) {
                setBadgeSuccess(this.alertBadges.uppercase);
            } else {
                setBadgeError(this.alertBadges.uppercase);
                hasErrors = true;
            }
        }

        // Lowercase validation
        if ('lowercase' in this.alertBadges) {
            if ((/[a-z]/.test(value))) {
                setBadgeSuccess(this.alertBadges.lowercase);
            } else {
                setBadgeError(this.alertBadges.lowercase);
                hasErrors = true;
            }
        }

        // Chars validation
        if ('symbol' in this.alertBadges) {
            if ((/[`!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~]/.test(value))) {
                setBadgeSuccess(this.alertBadges.symbol);
            } else {
                setBadgeError(this.alertBadges.symbol);
                hasErrors = true;
            }
        }

        // Numbers validation
        if ('number' in this.alertBadges) {
            if ((/\d/.test(value))) {
                setBadgeSuccess(this.alertBadges.number);
            } else {
                setBadgeError(this.alertBadges.number);
                hasErrors = true;
            }
        }

        // Length validation
        if ('length' in this.alertBadges) {
            if (value.length >= 8) {
                setBadgeSuccess(this.alertBadges.length);
            } else {
                setBadgeError(this.alertBadges.length);
                hasErrors = true;
            }
        }

        this.input.setCustomValidity('');

        return !hasErrors;
    };

    this.bindEvents = () => {
        this.$input.on({
            'show.bs.password': function(event) {
                this.debug(event);
            }.bind(this),
            'shown.bs.password': function(event) {
                this.debug(event);
            }.bind(this),
            'hide.bs.password': function(event) {
                this.debug(event);
            }.bind(this),
            'focus': function(event) {
                this.debug(event);
            }.bind(this),
            'input': function () {
                if (this.options.clientValidation) {
                    this.validate();
                }
            }.bind(this),
        });

        if (this.form && this.options.clientValidation) {
            const submitBtn = this.form.querySelectorAll('button[type="submit"]');

            submitBtn ?
                $(submitBtn).on('click', this.formSubmitHandler.bind(this)) :
                $(this.form).on('submit', this.formSubmitHandler.bind(this));
        }
    };

    this.debug = (event) => {
        if (!DEFAULTS.debug) {
            return;
        }

        if (window.console && window.console.log) {
            window.console.log(event.type + '.' + event.namespace + ': ', event);
        }
    };

    this.init = () => {
        this.initConfig();

        this.$input.attr('type', 'password');
        this.$input.wrap(this.options.templates.wrap);

        if (this.options.appendImg) {
            let icon = this.options.templates.icon
                .replace('{append}', this.options.append)
                .replace('{tootltip}',this.options.tooltip);

            this.$icon = $(['<div class="input-group-' + this.options.append	+ '"><span class="input-group-text" title="' + this.options.tooltip + '">' +
            '<img src="'
            + this.options.appendImg + '" aria-hidden="true"></span></div>'].join('')).css('cursor', 'pointer');
        } else {
            let icon = this.options.templates.icon
                .replace('{append}', this.options.append)
                .replace('{tootltip}',this.options.tooltip)
                .replace('{iconPrefix}', this.options.iconPrefix)
                .replace('{iconShow}', this.options.iconShow);

            this.$icon = $(['<div class="input-group-' + this.options.append	+ '"><span class="input-group-text" title="' + this.options.tooltip + '"><i class="'
            + this.options.iconPrefix + ' '
            + this.options.iconShow + '" aria-hidden="true"></i></span></div>'].join('')).css('cursor', 'pointer');
        }





        if (this.options.append === 'prepend') {
            this.$icon.insertBefore(this.$input);
        } else {
            this.$icon.insertAfter(this.$input);

            this.bindEvents();

            this.$icon.off('click').on('click', $.proxy(function() {
                this.toggle();
            }, this));
        }

        if (this.options.clientValidation) {
            this.renderAlerts();
            this.validate();
        }
    };

    this.init();
}

$.fn.password = function(options) {
    this.each(function() {
        const $this = $(this);
        let settings = $.extend($this.data(), options);
        $this.data('bs.password', new Password($this, settings));
    });

    return this;
};

$(function () {
    $('[data-toggle="password"]').password();
});
