(() => {
    const languageCookie = 'ak2-language';
    const translations = {
        en: {
            'nav-home': 'Home',
            'nav-shop': 'Shop',
            'nav-categories': 'Categories',
            'nav-about': 'About Us',
            'nav-contact': 'Contact',
            'shopping-cart': 'Shopping cart',
            'cart-title': 'Shopping Cart ({count})',
            quantity: 'Quantity',
            subtotal: 'Subtotal',
            'view-cart': 'View Cart',
            checkout: 'Checkout',
            'cart-empty': 'Your shopping cart is empty.',
            'start-shopping': 'Start Shopping',
            'account-menu': 'Account menu',
            account: 'Account',
            welcome: 'Welcome',
            'sign-in-prompt': 'Sign in to your account',
            login: 'Login',
            'create-account': 'Create Account',
            'manage-account': 'Manage your account',
            'my-account': 'My Account',
            logout: 'Logout',
            'open-menu': 'Open menu',
            'close-menu': 'Close menu',
            'theme-switch-light': 'Switch to light mode',
            'theme-switch-dark': 'Switch to dark mode',
            'language-switch': 'Switch language',
            'footer-description': 'Discover your identity through fashion. Quality, confidence and style in every detail.',
            instagram: 'Instagram',
            facebook: 'Facebook',
            tiktok: 'TikTok',
            explore: 'Explore',
            'customer-service': 'Customer Service',
            'contact-us': 'Contact Us',
            'get-in-touch': 'Get In Touch',
            'footer-email': 'Email: support@ak2.com',
            'footer-location': 'Location: Egypt',
            'footer-help': 'We are here to help you.',
            'footer-copyright': 'AK2 Fashion Store. All Rights Reserved.',
            'footer-secondary': 'Designed with Purpose. Built for Style.',
            'categories-eyebrow': 'EXPLORE AK2',
            'categories-title': 'Find your collection',
            'categories-intro': 'Explore the collections, then choose a category to see available styles.',
            'category-collection': 'COLLECTION',
            'category-card-description': 'Discover the collection and find your next look.',
            'explore-collection': 'EXPLORE COLLECTION',
            subcategories: 'Subcategories of {category}',
            'categories-empty-eyebrow': 'COMING SOON',
            'categories-empty-title': 'Collections are being prepared.',
            'categories-empty-description': 'Browse all available products while we add more collections.',
            'shop-all-products': 'SHOP ALL PRODUCTS',
            'products-eyebrow': 'AK2 COLLECTION',
            'products-title-prefix': 'Discover Your',
            'products-title-emphasis': 'Style',
            'products-intro': 'Explore our latest fashion collection.',
            'all-products': 'All Products',
            'products-found': '{count} products found',
            'product-image-placeholder': 'Image coming soon',
            featured: 'Featured',
            'view-product': 'View Product',
            'products-empty-title': 'No Products Found',
            'products-empty-description': 'There are currently no products in this category.',
            'browse-all-products': 'Browse All Products',
            'pagination-navigation': 'Pagination Navigation',
            'pagination-previous': 'Previous',
            'pagination-next': 'Next',
            'pagination-page': 'Go to page {page}',
            'pagination-showing': 'Showing',
            'pagination-to': 'to',
            'pagination-of': 'of',
            'pagination-results': 'results',
            breadcrumb: 'Breadcrumb',
            'product-description': 'Product Description',
            'select-option': 'Select Your Option',
            'size-color': 'Size / Color',
            'choose-option': 'Choose an option',
            'variant-option': '{size}{color}{price} - {availability}{stockSuffix}',
            'variant-standard': 'Standard',
            'in-stock': 'In Stock',
            'out-of-stock': 'Out of Stock',
            'currently-out-of-stock': 'Currently Out of Stock',
            availability: 'Availability',
            'add-to-cart': 'Add to Cart',
            'product-details-note': 'Product information and availability are subject to change.',
            'cart-page-title': 'Shopping Cart',
            'cart-page-intro': 'Review your selected products before checkout.',
            'cart-updated-success': 'Cart quantity updated successfully.',
            'cart-removed-success': 'Product removed from your cart successfully.',
            'cart-image-placeholder': 'No Image',
            size: 'Size',
            color: 'Color',
            update: 'Update',
            remove: 'Remove',
            'unit-price': 'Unit Price:',
            'order-summary': 'Order Summary',
            shipping: 'Shipping',
            'shipping-at-checkout': 'Calculated at checkout',
            total: 'Total',
            'proceed-checkout': 'Proceed to Checkout',
            'continue-shopping': 'Continue Shopping',
            'cart-empty-title': 'Your Cart Is Empty',
            'cart-empty-description': "You haven't added any products to your cart yet.",
            'explore-products': 'Explore Products',
            'checkout-eyebrow': 'AK2 secure checkout',
            'checkout-title': 'Checkout',
            'checkout-progress': 'Checkout progress',
            delivery: 'Delivery',
            review: 'Review',
            confirmation: 'Confirmation',
            'order-success-title': 'Thank you for your order!',
            'order-success-message': 'Your order has been placed successfully.',
            'order-number-label': 'Order Number:',
            'order-success-note': 'Keep this number for your records.',
            'checkout-error-title': 'Please review the highlighted information.',
            'customer-information': 'Customer Information',
            'full-name': 'Full Name *',
            email: 'Email',
            'phone-number': 'Phone Number *',
            'shipping-address': 'Shipping Address',
            address: 'Address *',
            'address-placeholder': 'Street, building, apartment...',
            city: 'City *',
            state: 'State / Governorate',
            'postal-code': 'Postal Code',
            'payment-method': 'Payment Method',
            'cash-on-delivery': 'Cash on Delivery',
            'cash-on-delivery-description': 'Pay when your order arrives. No card details are collected at checkout.',
            'order-notes': 'Order Notes',
            'notes-placeholder': 'Additional delivery instructions...',
            'place-order': 'Place cash-on-delivery order',
            'your-order': 'Your Order',
            free: 'Free',
            'checkout-cart-empty': 'Your cart is empty',
            'browse-products': 'Browse Products',
            'title-categories': 'AK2 | Categories',
            'title-products': 'AK2 | Shop',
            'title-home': 'AK2 | Fashion Store',
            'title-cart': 'AK2 | Shopping Cart',
            'title-checkout': 'Checkout | AK2',
            'title-product': 'AK2 | {name}',
            'home-hero-eyebrow': 'AK2 FASHION STORE',
            'home-hero-title-first': 'YOUR STYLE.',
            'home-hero-title-second': 'YOUR IDENTITY.',
            'home-hero-description': 'Discover the latest fashion collections and express yourself with AK2.',
            'home-image-placeholder': 'YOUR NEXT LOOK STARTS HERE',
            'home-no-featured-products': 'No featured products are published yet.',
            'home-no-new-products': 'No published products are available yet.',
            style: 'STYLE',
            identity: 'IDENTITY',
            subscribe: 'SUBSCRIBE',
            new: 'NEW',
        },
        ar: {
            'nav-home': 'الرئيسية',
            'nav-shop': 'المتجر',
            'nav-categories': 'التصنيفات',
            'nav-about': 'من نحن',
            'nav-contact': 'اتصل بنا',
            'shopping-cart': 'سلة التسوق',
            'cart-title': 'سلة التسوق ({count})',
            quantity: 'الكمية',
            subtotal: 'المجموع الفرعي',
            'view-cart': 'عرض السلة',
            checkout: 'إتمام الشراء',
            'cart-empty': 'سلة التسوق فارغة.',
            'start-shopping': 'ابدأ التسوق',
            'account-menu': 'قائمة الحساب',
            account: 'الحساب',
            welcome: 'مرحباً',
            'sign-in-prompt': 'سجّل الدخول إلى حسابك',
            login: 'تسجيل الدخول',
            'create-account': 'إنشاء حساب',
            'manage-account': 'إدارة حسابك',
            'my-account': 'حسابي',
            logout: 'تسجيل الخروج',
            'open-menu': 'فتح القائمة',
            'close-menu': 'إغلاق القائمة',
            'theme-switch-light': 'التبديل إلى الوضع الفاتح',
            'theme-switch-dark': 'التبديل إلى الوضع الداكن',
            'language-switch': 'تغيير اللغة',
            'footer-description': 'اكتشف هويتك من خلال الأزياء. جودة وثقة وأناقة في كل التفاصيل.',
            instagram: 'إنستغرام',
            facebook: 'فيسبوك',
            tiktok: 'تيك توك',
            explore: 'استكشف',
            'customer-service': 'خدمة العملاء',
            'contact-us': 'اتصل بنا',
            'get-in-touch': 'تواصل معنا',
            'footer-email': 'البريد الإلكتروني: support@ak2.com',
            'footer-location': 'الموقع: مصر',
            'footer-help': 'نحن هنا لمساعدتك.',
            'footer-copyright': 'متجر AK2 للأزياء. جميع الحقوق محفوظة.',
            'footer-secondary': 'تصميم هادف. وأناقة متقنة.',
            'categories-eyebrow': 'اكتشف AK2',
            'categories-title': 'اعثر على مجموعتك',
            'categories-intro': 'استكشف المجموعات، ثم اختر تصنيفاً للاطلاع على المنتجات المتاحة.',
            'category-collection': 'مجموعة',
            'category-card-description': 'اكتشف المجموعة واختر إطلالتك القادمة.',
            'explore-collection': 'استكشف المجموعة',
            subcategories: 'التصنيفات الفرعية لـ {category}',
            'categories-empty-eyebrow': 'قريباً',
            'categories-empty-title': 'نعمل على تجهيز المجموعات.',
            'categories-empty-description': 'تصفح المنتجات المتاحة بينما نضيف المزيد من المجموعات.',
            'shop-all-products': 'تسوق جميع المنتجات',
            'products-eyebrow': 'مجموعة AK2',
            'products-title-prefix': 'اكتشف',
            'products-title-emphasis': 'أسلوبك',
            'products-intro': 'تصفح أحدث مجموعاتنا.',
            'all-products': 'جميع المنتجات',
            'products-found': 'عدد المنتجات: {count}',
            'product-image-placeholder': 'الصورة قريباً',
            featured: 'مميز',
            'view-product': 'عرض المنتج',
            'products-empty-title': 'لم يتم العثور على منتجات',
            'products-empty-description': 'لا توجد منتجات متاحة حالياً في هذا التصنيف.',
            'browse-all-products': 'تصفح جميع المنتجات',
            'pagination-navigation': 'التنقل بين الصفحات',
            'pagination-previous': 'السابق',
            'pagination-next': 'التالي',
            'pagination-page': 'انتقل إلى الصفحة {page}',
            'pagination-showing': 'عرض',
            'pagination-to': 'إلى',
            'pagination-of': 'من',
            'pagination-results': 'نتيجة',
            breadcrumb: 'مسار التنقل',
            'product-description': 'وصف المنتج',
            'select-option': 'اختر الخيار',
            'size-color': 'المقاس / اللون',
            'choose-option': 'اختر خياراً',
            'variant-option': '{size}{color}{price} - {availability}{stockSuffix}',
            'variant-standard': 'قياسي',
            'in-stock': 'متوفر',
            'out-of-stock': 'نفد المخزون',
            'currently-out-of-stock': 'غير متوفر حالياً',
            availability: 'التوفر',
            'add-to-cart': 'أضف إلى السلة',
            'product-details-note': 'قد تتغير معلومات المنتج ومدى توفره.',
            'cart-page-title': 'سلة التسوق',
            'cart-page-intro': 'راجع المنتجات التي اخترتها قبل إتمام الشراء.',
            'cart-updated-success': 'تم تحديث كمية المنتج في السلة بنجاح.',
            'cart-removed-success': 'تمت إزالة المنتج من سلتك بنجاح.',
            'cart-image-placeholder': 'لا توجد صورة',
            size: 'المقاس',
            color: 'اللون',
            update: 'تحديث',
            remove: 'إزالة',
            'unit-price': 'سعر الوحدة:',
            'order-summary': 'ملخص الطلب',
            shipping: 'الشحن',
            'shipping-at-checkout': 'يُحتسب عند إتمام الشراء',
            total: 'الإجمالي',
            'proceed-checkout': 'المتابعة لإتمام الشراء',
            'continue-shopping': 'متابعة التسوق',
            'cart-empty-title': 'سلتك فارغة',
            'cart-empty-description': 'لم تضف أي منتجات إلى سلتك بعد.',
            'explore-products': 'استكشف المنتجات',
            'checkout-eyebrow': 'إتمام آمن من AK2',
            'checkout-title': 'إتمام الطلب',
            'checkout-progress': 'مراحل إتمام الطلب',
            delivery: 'التوصيل',
            review: 'المراجعة',
            confirmation: 'التأكيد',
            'order-success-title': 'شكراً لطلبك!',
            'order-success-message': 'تم تقديم طلبك بنجاح.',
            'order-number-label': 'رقم الطلب:',
            'order-success-note': 'احتفظ بهذا الرقم للرجوع إليه.',
            'checkout-error-title': 'يرجى مراجعة البيانات الموضحة.',
            'customer-information': 'بيانات العميل',
            'full-name': 'الاسم الكامل *',
            email: 'البريد الإلكتروني',
            'phone-number': 'رقم الهاتف *',
            'shipping-address': 'عنوان التوصيل',
            address: 'العنوان *',
            'address-placeholder': 'الشارع، المبنى، الشقة...',
            city: 'المدينة *',
            state: 'المحافظة / المنطقة',
            'postal-code': 'الرمز البريدي',
            'payment-method': 'طريقة الدفع',
            'cash-on-delivery': 'الدفع عند الاستلام',
            'cash-on-delivery-description': 'ادفع عند وصول طلبك. لا نجمع بيانات البطاقات أثناء إتمام الطلب.',
            'order-notes': 'ملاحظات الطلب',
            'notes-placeholder': 'تعليمات إضافية للتوصيل...',
            'place-order': 'تأكيد طلب الدفع عند الاستلام',
            'your-order': 'طلبك',
            free: 'مجاني',
            'checkout-cart-empty': 'سلة التسوق فارغة',
            'browse-products': 'تصفح المنتجات',
            'title-categories': 'AK2 | التصنيفات',
            'title-products': 'AK2 | المتجر',
            'title-home': 'AK2 | متجر الأزياء',
            'title-cart': 'AK2 | سلة التسوق',
            'title-checkout': 'إتمام الطلب | AK2',
            'title-product': 'AK2 | {name}',
            'home-hero-eyebrow': 'متجر AK2 للأزياء',
            'home-hero-title-first': 'أسلوبك.',
            'home-hero-title-second': 'هويتك.',
            'home-hero-description': 'اكتشف أحدث مجموعات الأزياء وعبّر عن نفسك مع AK2.',
            'home-image-placeholder': 'إطلالتك الجديدة تبدأ هنا',
            'home-no-featured-products': 'لا توجد منتجات مميزة منشورة حالياً.',
            'home-no-new-products': 'لا توجد منتجات منشورة حالياً.',
            style: 'الأناقة',
            identity: 'الهوية',
            subscribe: 'اشترك',
            new: 'جديد',
        },
    };

    const currentLanguage = () => {
        const preference = document.cookie
            .split(';')
            .map((cookie) => cookie.trim())
            .find((cookie) => cookie.startsWith(`${languageCookie}=`))
            ?.split('=')[1];

        if (preference === 'ar' || preference === 'en') {
            return preference;
        }

        return document.documentElement.lang === 'ar' ? 'ar' : 'en';
    };

    const setLanguageCookie = (language) => {
        const secure = window.location.protocol === 'https:' ? '; Secure' : '';

        document.cookie = `${languageCookie}=${language}; Max-Age=31536000; Path=/; SameSite=Lax${secure}`;
    };

    const updateLanguage = (language) => {
        const root = document.documentElement;
        const strings = translations[language];
        const localizedAttribute = (element, attribute) => element.getAttribute(`data-storefront-${attribute}-${language}`);
        const languageSuffix = language.charAt(0).toUpperCase() + language.slice(1);
        const setLocalizedText = (element, value) => {
            element.textContent = '';

            value.split(/\r\n|\r|\n/).forEach((line, index) => {
                if (index > 0) {
                    element.append(document.createElement('br'));
                }

                element.append(document.createTextNode(line));
            });
        };
        const interpolate = (template, dataset) => template.replace(/\{([A-Za-z]+)\}/g, (match, key) => {
            const dataKey = `i18n${key.charAt(0).toUpperCase()}${key.slice(1)}`;
            const value = dataset[dataKey];

            if (value === undefined) {
                return '';
            }

            if (key === 'availability') {
                return strings[value] ?? value;
            }

            if (key === 'size') {
                const localizedSize = dataset[`storefrontSize${languageSuffix}`];

                if (localizedSize !== undefined) {
                    return localizedSize;
                }

                return strings[`variant-${value.toLowerCase()}`] ?? value;
            }

            if (key === 'color') {
                const localizedColor = dataset[`storefrontColor${languageSuffix}`];

                if (localizedColor !== undefined) {
                    return localizedColor;
                }
            }

            if (key === 'category') {
                const localizedCategory = dataset[`storefrontCategory${languageSuffix}`];

                if (localizedCategory !== undefined) {
                    return localizedCategory;
                }
            }

            return value;
        });

        const pageTitleKey = document.querySelector('.categories-page')
            ? 'title-categories'
            : document.querySelector('.products-page')
                ? 'title-products'
                : document.querySelector('.hero-section')
                    ? 'title-home'
                : document.querySelector('.cart-page')
                    ? 'title-cart'
                    : document.querySelector('.checkout-page')
                        ? 'title-checkout'
                        : document.querySelector('.product-details-page h1')
                            ? 'title-product'
                            : null;

        if (pageTitleKey) {
            const pageName = document.querySelector('.product-details-page h1')?.textContent.trim() ?? '';

            document.title = interpolate(strings[pageTitleKey], { i18nName: pageName });
        }

        document.querySelectorAll('[data-storefront-content-en], [data-storefront-content-ar]').forEach((element) => {
            const value = localizedAttribute(element, 'content');

            if (value !== null) {
                setLocalizedText(element, value);
            }
        });

        document.querySelectorAll('[data-storefront-alt-en], [data-storefront-alt-ar]').forEach((element) => {
            const value = localizedAttribute(element, 'alt');

            if (value !== null) {
                element.setAttribute('alt', value);
            }
        });

        document.querySelectorAll('[data-storefront-placeholder-en], [data-storefront-placeholder-ar]').forEach((element) => {
            const value = localizedAttribute(element, 'placeholder');

            if (value !== null) {
                element.setAttribute('placeholder', value);
            }
        });

        document.querySelectorAll('[data-storefront-aria-label-en], [data-storefront-aria-label-ar]').forEach((element) => {
            const value = localizedAttribute(element, 'aria-label');

            if (value !== null) {
                element.setAttribute('aria-label', value);
            }
        });

        const title = document.querySelector('title[data-storefront-meta-title-en][data-storefront-meta-title-ar]');
        const description = document.querySelector('meta[data-storefront-meta-description-en][data-storefront-meta-description-ar]');

        if (title) {
            title.textContent = localizedAttribute(title, 'meta-title') ?? '';
        }

        if (description) {
            description.setAttribute('content', localizedAttribute(description, 'meta-description') ?? '');
        }

        root.lang = language;
        root.dir = language === 'ar' ? 'rtl' : 'ltr';

        document.querySelectorAll('[data-i18n]').forEach((element) => {
            const translated = strings[element.dataset.i18n];

            if (translated) {
                element.textContent = interpolate(translated, element.dataset);
            }
        });

        document.querySelectorAll('[data-i18n-aria]').forEach((element) => {
            const translated = strings[element.dataset.i18nAria];

            if (translated) {
                element.setAttribute(
                    'aria-label',
                    interpolate(translated, element.dataset),
                );
            }
        });

        document.querySelectorAll('[data-i18n-placeholder]').forEach((element) => {
            const translated = strings[element.dataset.i18nPlaceholder];

            if (translated) {
                element.setAttribute('placeholder', translated);
            }
        });

        const pagination = document.querySelector('.products-pagination nav');

        if (pagination) {
            pagination.setAttribute('aria-label', strings['pagination-navigation']);

            pagination.querySelectorAll('a[rel="prev"], a[rel="next"]').forEach((link) => {
                const key = link.getAttribute('rel') === 'prev' ? 'pagination-previous' : 'pagination-next';

                link.setAttribute('aria-label', strings[key]);
                link.childNodes.forEach((node) => {
                    if (node.nodeType === Node.TEXT_NODE && node.textContent.trim()) {
                        node.textContent = ` ${strings[key]} `;
                    }
                });
            });

            pagination.querySelectorAll('span[aria-label]').forEach((link) => {
                const label = link.getAttribute('aria-label');
                const key = ['Previous', 'السابق'].includes(label)
                    ? 'pagination-previous'
                    : ['Next', 'التالي'].includes(label)
                        ? 'pagination-next'
                        : null;

                if (key) {
                    link.setAttribute('aria-label', strings[key]);
                }
            });

            pagination.querySelectorAll('span.cursor-not-allowed').forEach((link) => {
                const textNode = [...link.childNodes].find((node) =>
                    node.nodeType === Node.TEXT_NODE && /previous|next|السابق|التالي/i.test(node.textContent)
                );

                if (textNode) {
                    const key = /previous|السابق/i.test(textNode.textContent)
                        ? 'pagination-previous'
                        : 'pagination-next';

                    textNode.textContent = ` ${strings[key]} `;
                    link.setAttribute('aria-label', strings[key]);
                }
            });

            pagination.querySelectorAll('a[aria-label]').forEach((link) => {
                const page = link.getAttribute('aria-label').match(/(?:Go to page |انتقل إلى الصفحة )(\d+)/)?.[1];

                if (page) {
                    link.setAttribute('aria-label', strings['pagination-page'].replace('{page}', page));
                }
            });

            const summary = pagination.querySelector('p');

            if (summary) {
                const words = {
                    Showing: 'pagination-showing',
                    'عرض': 'pagination-showing',
                    to: 'pagination-to',
                    'إلى': 'pagination-to',
                    of: 'pagination-of',
                    'من': 'pagination-of',
                    results: 'pagination-results',
                    'نتيجة': 'pagination-results',
                };

                summary.childNodes.forEach((node) => {
                    if (node.nodeType === Node.TEXT_NODE) {
                        const word = node.textContent.trim();

                        if (words[word]) {
                            node.textContent = node.textContent.replace(word, strings[words[word]]);
                        }
                    }
                });
            }
        }

        document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
            const themeKey = root.dataset.theme === 'light' ? 'theme-switch-dark' : 'theme-switch-light';

            button.setAttribute('aria-label', strings[themeKey]);
            button.setAttribute('title', strings[themeKey]);
        });

        const languageButton = document.querySelector('[data-language-toggle]');

        if (languageButton) {
            languageButton.setAttribute('aria-label', strings['language-switch']);
            languageButton.querySelector('[data-language-label]').textContent = language === 'ar' ? 'EN' : 'عربي';
        }
    };

    document.addEventListener('DOMContentLoaded', () => {
        updateLanguage(currentLanguage());

        document.querySelector('[data-language-toggle]')?.addEventListener('click', () => {
            const nextLanguage = currentLanguage() === 'ar' ? 'en' : 'ar';

            setLanguageCookie(nextLanguage);
            updateLanguage(nextLanguage);
        });

        document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
            button.addEventListener('click', () => {
                window.setTimeout(() => updateLanguage(currentLanguage()), 0);
            });
        });

        document.querySelector('#mobileMenuToggle')?.addEventListener('click', (event) => {
            const menuButton = event.currentTarget;

            window.setTimeout(() => {
                const key = menuButton.getAttribute('aria-expanded') === 'true'
                    ? 'close-menu'
                    : 'open-menu';

                menuButton.setAttribute('aria-label', translations[currentLanguage()][key]);
            }, 0);
        });
    });
})();