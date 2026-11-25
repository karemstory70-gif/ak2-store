(() => {
    const cookieName = 'ak2-language';
    const errorTranslations = {
        'These credentials do not match our records.': 'بيانات الدخول غير صحيحة. تحقق من البريد الإلكتروني وكلمة المرور.',
        'The name field is required.': 'الاسم الكامل مطلوب.',
        'The email field is required.': 'البريد الإلكتروني مطلوب.',
        'The email must be a valid email address.': 'أدخل بريدًا إلكترونيًا صالحًا.',
        'The email field must be a valid email address.': 'أدخل بريدًا إلكترونيًا صالحًا.',
        'The email has already been taken.': 'هذا البريد الإلكتروني مسجل بالفعل.',
        'The password field is required.': 'كلمة المرور مطلوبة.',
        'The password field confirmation does not match.': 'تأكيد كلمة المرور غير مطابق.',
        'The password confirmation does not match.': 'تأكيد كلمة المرور غير مطابق.',
        'The password field must contain at least one letter.': 'يجب أن تحتوي كلمة المرور على حرف واحد على الأقل.',
        'The password field must contain at least one uppercase and one lowercase letter.': 'يجب أن تحتوي كلمة المرور على حرف كبير وحرف صغير على الأقل.',
        'The password field must contain at least one number.': 'يجب أن تحتوي كلمة المرور على رقم واحد على الأقل.',
        'The password field must contain at least one symbol.': 'يجب أن تحتوي كلمة المرور على رمز واحد على الأقل.',
        'The password field has appeared in a data leak. Please choose a different password.': 'ظهرت كلمة المرور ضمن بيانات مسرّبة. اختر كلمة مرور أخرى.',
    };
    let activeLanguage = 'en';

    const readLanguageCookie = () => document.cookie
        .split(';')
        .map((cookie) => cookie.trim())
        .find((cookie) => cookie.startsWith(`${cookieName}=`))
        ?.split('=')[1];

    const currentLanguage = () => {
        const preference = readLanguageCookie();

        return preference === 'ar' || preference === 'en'
            ? preference
            : document.documentElement.lang === 'ar' ? 'ar' : 'en';
    };

    const setLanguageCookie = (language) => {
        const secure = window.location.protocol === 'https:' ? '; Secure' : '';

        document.cookie = `${cookieName}=${language}; Max-Age=31536000; Path=/; SameSite=Lax${secure}`;
    };

    const translateError = (message) => {
        if (errorTranslations[message]) {
            return errorTranslations[message];
        }

        const minimumPasswordLength = message.match(/^The password field must be at least (\d+) characters\.$/);

        if (minimumPasswordLength) {
            return `يجب ألا تقل كلمة المرور عن ${minimumPasswordLength[1]} أحرف.`;
        }

        const maximumLength = message.match(/^The (name|email) field must not be greater than (\d+) characters\.$/);

        if (maximumLength) {
            const field = maximumLength[1] === 'name' ? 'الاسم' : 'البريد الإلكتروني';

            return `يجب ألا يتجاوز ${field} ${maximumLength[2]} حرفًا.`;
        }

        const throttleMessage = message.match(/^Too many login attempts\. Please try again in (\d+) seconds?\.$/);

        if (throttleMessage) {
            return `محاولات كثيرة. حاول مرة أخرى بعد ${throttleMessage[1]} ثانية.`;
        }

        return message;
    };

    const updateErrors = (language) => {
        document.querySelectorAll('.ak2-auth-form [data-flux-error]').forEach((error) => {
            const displayedMessage = error.textContent.trim();
            const hasOriginalMessage = Object.prototype.hasOwnProperty.call(error.dataset, 'authErrorEn');
            const previousTranslation = hasOriginalMessage ? translateError(error.dataset.authErrorEn) : null;

            if (!hasOriginalMessage || (displayedMessage !== error.dataset.authErrorEn && displayedMessage !== previousTranslation)) {
                error.dataset.authErrorEn = displayedMessage;
            }

            const message = language === 'ar'
                ? translateError(error.dataset.authErrorEn)
                : error.dataset.authErrorEn;

            if (displayedMessage !== message) {
                error.textContent = message;
            }
        });
    };

    const updateLanguage = (language) => {
        const root = document.documentElement;
        const suffix = language === 'ar' ? 'Ar' : 'En';

        activeLanguage = language;
        root.lang = language;
        root.dir = language === 'ar' ? 'rtl' : 'ltr';

        document.querySelectorAll('[data-auth-text-en][data-auth-text-ar]').forEach((element) => {
            element.textContent = element.dataset[`authText${suffix}`];
        });

        document.querySelectorAll('[data-auth-label-en][data-auth-label-ar]').forEach((control) => {
            const label = control.closest('[data-flux-field]')?.querySelector('[data-flux-label]');

            if (label) {
                label.textContent = control.dataset[`authLabel${suffix}`];
            }
        });

        document.querySelectorAll('[data-auth-placeholder-en][data-auth-placeholder-ar]').forEach((input) => {
            input.placeholder = input.dataset[`authPlaceholder${suffix}`];
        });

        updateErrors(language);

        document.querySelectorAll('[data-auth-language-toggle]').forEach((button) => {
            const targetLanguage = language === 'ar' ? 'en' : 'ar';

            button.setAttribute('aria-label', targetLanguage === 'ar' ? 'Switch language to Arabic' : 'التبديل إلى الإنجليزية');
            button.setAttribute('title', targetLanguage === 'ar' ? 'Switch language to Arabic' : 'التبديل إلى الإنجليزية');
            button.querySelector('[data-auth-language-label]').textContent = targetLanguage === 'ar' ? 'عربي' : 'EN';
        });

        const title = root.dataset[`authTitle${suffix}`];

        if (title) {
            document.title = title;
        }
    };

    document.addEventListener('DOMContentLoaded', () => {
        updateLanguage(currentLanguage());

        new MutationObserver(() => updateErrors(activeLanguage))
            .observe(document.body, { childList: true, characterData: true, subtree: true });

        document.querySelectorAll('[data-auth-language-toggle]').forEach((button) => {
            button.addEventListener('click', () => {
                const nextLanguage = currentLanguage() === 'ar' ? 'en' : 'ar';

                setLanguageCookie(nextLanguage);
                updateLanguage(nextLanguage);
            });
        });
    });
})();
