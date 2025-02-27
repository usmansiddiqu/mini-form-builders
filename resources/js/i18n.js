import { createI18n } from 'vue-i18n';


import en from './lang/en.json';
import fr from './lang/fr.json';

const i18n = createI18n({
    legacy: false,
    globalInjection: true,
    locale: localStorage.getItem('locale') || 'en',
    fallbackLocale: 'en',
    messages: { en, fr }
});

export default i18n;
