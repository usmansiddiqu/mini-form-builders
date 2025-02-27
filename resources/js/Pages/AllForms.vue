<script>
import SideBar from '@/Components/SideBar.vue';
import { apiRequest } from '@/utils/api';

export default {
  data() {
    return {
      forms: [],
      formData: {},
    };
  },
  async mounted() {
    await this.fetchForms();
  },
  methods: {
    async fetchForms() {
      try {
        const response = await apiRequest("forms", "GET");
        console.log("API Raw Response:", response);

        if (Array.isArray(response)) {
          this.forms = response.map(form => ({
            ...form,
            fields: Array.isArray(form.fields) ? form.fields.map(field => ({
              ...field,
              options: field.options ? this.parseOptions(field.options) : [],
            })) : [],
          }));

          console.log("Processed Forms:", this.forms);

          if (this.forms.length === 0) {
            console.warn("No forms available.");
          } else {
            this.initializeFormData();
          }
        } else {
          console.error("Unexpected API response format:", response);
        }
      } catch (error) {
        console.error("Error fetching forms:", error);
      }
    },

    parseOptions(options) {
      try {
        return typeof options === "string" ? JSON.parse(options) : options;
      } catch (error) {
        console.error("Error parsing options:", options, error);
        return [];
      }
    },

    initializeFormData() {
      this.formData = {};
      this.forms.forEach(form => {
        this.formData[form.id] = {};
        form.fields.forEach(field => {
          this.formData[form.id][field.name || field.label] = "";
        });
      });
      console.log("Initialized formData:", this.formData);
    },

    async submitForm(formId) {
      console.log("Submitting form:", formId, this.formData[formId]);
      try {
        const response = await apiRequest(`forms/${formId}`, "POST", this.formData[formId]);
        console.log("Form submitted successfully:", response);
      } catch (error) {
        console.error("Error submitting form:", error);
      }
    },

    formatLabel(label) {
      return label.replace(/_/g, " ").toUpperCase();
    },

    getFormStyle(form) {
      return {
        backgroundColor: form.background_color || "#ffffff",
        fontFamily: form.font_family || "sans-serif",
        border: "1px solid #e5e7eb",
      };
    },
  },
};
</script>

<template>
    <div class="container mx-auto p-6">
      <SideBar />
      <h1 class="text-3xl font-bold text-center mb-8">Dynamic Forms</h1>
      <div v-if="forms.length === 0" class="text-center text-gray-500">No forms available</div>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="form in forms" :key="form.id" :style="getFormStyle(form)" class="bg-white shadow-lg rounded-xl p-6">
          <h2 class="text-2xl font-semibold mb-3">{{ form.title }}</h2>
          <p class="text-gray-600 mb-4">{{ form.description }}</p>
          <form @submit.prevent="submitForm(form.id)" class="space-y-4">
            <div v-for="field in form.fields" :key="field.id" class="mb-4">
              <label :for="field.name || field.label" class="block text-sm font-medium text-gray-700">
                {{ formatLabel(field.name || field.label) }}
              </label>

              <!-- Text Input -->
              <input v-if="field.type === 'text'"
                v-model="formData[form.id][field.name || field.label]"
                type="text"
                class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-400"
              />

              <!-- Dropdown -->
              <select v-else-if="field.type === 'dropdown'"
                v-model="formData[form.id][field.name || field.label]"
                class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-400"
              >
                <option v-for="option in field.options" :key="option" :value="option">
                  {{ option }}
                </option>
              </select>
              <button v-else-if="field.type === 'button'"
                type="button"
                class="mt-2 p-3 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 transition"
              >
                {{ formatLabel(field.name || field.label) }}
              </button>
            </div>

            <button type="submit"
              class="w-full p-3 bg-green-500 text-white font-semibold rounded-lg hover:bg-green-600 transition"
            >
              Submit
            </button>
          </form>
        </div>
      </div>
    </div>
  </template>

