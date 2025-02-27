<script setup>
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';
import { apiRequest } from '@/utils/api';
import Sidebar from '../Components/SideBar.vue';
const { t, locale } = useI18n();


// Form Fields
const formFields = ref([
    { id: 1, type: 'text', label: t('form.firstName'), value: '' },
    { id: 2, type: 'text', label: t('form.lastName'), value: '' }
]);

const saveForm = async () => {
    try {
        await apiRequest('forms', 'POST', {
            name: 'My Custom Form',
            background_color: backgroundColor.value,
            font_family: fontFamily.value,
            form_labels: formLabels.value,
            fields: formFields.value.map(field => ({
                type: field.type,
                label: field.label,
                value: 'test',
                   options: field.type === 'dropdown' || field.type === 'radio' ? ['Option 1', 'Option 2'] : null,

            })),
        });

        alert(t('formSavedSuccessfully'));
    } catch (error) {
        alert(t('errorSavingForm'));
    }
};

// Available Field Types
const fieldTypes = [
    { type: 'text', label: t('fields.text') },
    { type: 'button', label: t('fields.button') },
    { type: 'dropdown', label: t('fields.dropdown') },
    { type: 'radio', label: t('fields.radio') },
    { type: 'checkbox', label: t('fields.checkbox') },
    { type: 'switch', label: t('fields.switch') }
];

// UI State
const backgroundColor = ref('bg-white');
const fontFamily = ref('Roboto');
const formLabels = ref(true);

// Add New Field Dropdown
const isDropdownOpen = ref(false);
const selectedField = ref(null);

// Toggle Dropdown
const toggleDropdown = () => {
    isDropdownOpen.value = !isDropdownOpen.value;
};


// Add Selected Field
const addField = (type) => {
    formFields.value.push({
        id: Date.now(),
        type,
        label: t(`fields.${type}`),
        value: ''
    });
    isDropdownOpen.value = false;
};

// Change Language
const changeLanguage = (lang) => {
    locale.value = lang;
    localStorage.setItem('locale', lang);
};
</script>

<template>
    <div class="flex h-screen w-screen bg-gray-50 overflow-auto">
        <!-- Sidebar -->
         <Sidebar/>

        <!-- Main Content -->
        <div class="flex-1 p-10 bg-white shadow-lg rounded-r-lg">
            <div class="flex justify-between mb-8">
                <h2 class="text-3xl font-semibold text-gray-800">{{ t('title') }}</h2>
                <button @click="saveForm" class="bg-blue-600 text-white px-6 py-2 rounded-md shadow-md hover:bg-blue-700 transition ease-in-out duration-200">
                    {{ t('publish') }}
                </button>
            </div>

            <div class="flex gap-8">
                <!-- Form Builder -->
                <div class="p-6 border rounded-lg shadow-md w-2/3 bg-white">
                    <h3 class="text-xl font-semibold mb-4 text-gray-700">{{ t('form.title') }}</h3>
                    <div v-for="field in formFields" :key="field.id" class="mb-6">
                        <label v-if="formLabels" class="block mb-2 text-gray-700 font-medium">{{ field.label }}</label>

                        <!-- Text Input Field -->
                        <input v-if="field.type === 'text'" type="text" v-model="field.value" class="w-full p-4 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />

                        <!-- Dropdown Field -->
                        <select v-if="field.type === 'dropdown'" v-model="field.value" class="w-full p-4 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option>{{ t('fields.option1') }}</option>
                            <option>{{ t('fields.option2') }}</option>
                        </select>

                        <!-- Checkbox Field -->
                        <div v-if="field.type === 'checkbox'" class="flex items-center">
                            <input type="checkbox" v-model="field.value" class="mr-4 h-5 w-5 text-blue-600 rounded-md focus:ring-2 focus:ring-blue-500" />
                            <span class="text-gray-700">{{ t('fields.checkboxLabel') }}</span>
                        </div>

                        <!-- Radio Button Field -->
                        <div v-if="field.type === 'radio'" class="flex items-center">
                            <input type="radio" v-model="field.value" class="mr-4 h-5 w-5 text-blue-600 rounded-md focus:ring-2 focus:ring-blue-500" />
                            <span class="text-gray-700">{{ t('fields.radioLabel') }}</span>
                        </div>

                        <!-- Button Field -->
                        <div v-if="field.type === 'button'" class="flex items-center">
                            <button class="w-full py-3 bg-blue-600 text-white rounded-lg shadow-md hover:bg-blue-700 transition ease-in-out duration-200">
                                {{ field.label }}
                            </button>
                        </div>

                        <!-- Switch Field -->
                        <div v-if="field.type === 'switch'" class="flex items-center">
                            <input type="checkbox" v-model="field.value" class="mr-4 h-5 w-5 text-blue-600 rounded-md focus:ring-2 focus:ring-blue-500" />
                            <span class="text-gray-700">{{ t('fields.switchLabel') }}</span>
                        </div>
                    </div>

                    <!-- Add Field Dropdown Button -->
                    <div class="relative mt-6">
                        <button @click="toggleDropdown"
                            class="w-full px-5 py-3 rounded-lg bg-gray-200 text-gray-800 font-semibold flex justify-between items-center hover:bg-gray-300 focus:outline-none">
                            {{ t('addField') }} <span class="text-gray-500">▼</span>
                        </button>

                        <!-- Dropdown Menu -->
                        <div v-if="isDropdownOpen"
                            class="absolute left-0 mt-2 w-full bg-white border rounded-lg shadow-lg z-10">
                            <input type="text" placeholder="Search..." class="w-full px-4 py-3 border-b outline-none focus:ring-2 focus:ring-blue-500" />
                            <ul>
                                <li v-for="field in fieldTypes" :key="field.type" @click="addField(field.type)"
                                    class="px-4 py-3 text-gray-700 cursor-pointer hover:bg-gray-100">
                                    {{ field.label }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Settings Panel -->
                <div class="p-6 border rounded-lg shadow-md w-1/3 bg-white">
                    <h3 class="text-xl font-semibold mb-4 text-gray-700">{{ t('settings') }}</h3>
                   <div class="mb-6">
                    <p class="mb-2 text-gray-600">{{ t('backgroundColor') }}</p>
                    <div class="flex gap-3">
                        <button v-for="color in ['#ffffff', '#f3f4f6', '#e5e7eb', '#93c5fd', '#fcd34d', '#fca5a5']"
                            :key="color"
                            :style="{ backgroundColor: color }"
                            class="w-8 h-8 rounded-full border border-gray-300 transition-all duration-200 ease-in-out"
                            :class="{ 'ring-2 ring-blue-500': backgroundColor === color }"
                            @click="backgroundColor = color">
                        </button>
                    </div>
                </div>

                    <div class="mb-6">
                        <p class="mb-2 text-gray-600">{{ t('fontFamily') }}</p>
                        <select v-model="fontFamily" class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500">
                            <option>Roboto</option>
                            <option>Arial</option>
                            <option>Times New Roman</option>
                        </select>
                    </div>
                    <div>
                        <p class="mb-2 text-gray-600">{{ t('formLabels') }}</p>
                        <label class="flex items-center gap-3">
                        <input type="checkbox" v-model="formLabels" :true-value="true" :false-value="false" class="h-5 w-5 text-blue-600 focus:ring-2 focus:ring-blue-500" />
                        <span>{{ formLabels ? t('turnedOn') : t('turnedOff') }}</span>
                    </label>
                    </div>
                </div>
            </div>

            <!-- Language Selector -->
            <div class="mt-8">
                <label class="mr-3 text-lg">{{ t('language') }}:</label>
                <select v-model="locale" @change="changeLanguage(locale)" class="px-6 py-2 rounded-lg border shadow-sm focus:ring-2 focus:ring-blue-500">
                    <option value="en">English</option>
                    <option value="fr">Français</option>
                </select>
            </div>
        </div>
    </div>
    <router-view></router-view>
</template>
