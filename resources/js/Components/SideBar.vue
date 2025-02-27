<script setup>
import { useI18n } from 'vue-i18n';
import { router } from '@inertiajs/vue3';

const { t } = useI18n();
</script>

<template>
    <div class="w-64 bg-gray-900 text-white p-6 rounded-l-lg shadow-md h-full">
        <h2 class="text-2xl font-semibold mb-8">{{ t('menu.title') }}</h2>
        <ul>
            <li @click="router.visit('/dashboard')" class="py-3 px-4 bg-gray-800 rounded mb-4 text-lg hover:bg-gray-700 cursor-pointer">
                {{ t('menu.myForms') }}
            </li>
            <li @click="router.visit('/forms')" class="py-3 px-4 bg-gray-800 rounded mb-4 text-lg hover:bg-gray-700 cursor-pointer">
                {{ t('menu.Forms') }}
            </li>
        </ul>
    </div>
</template>
