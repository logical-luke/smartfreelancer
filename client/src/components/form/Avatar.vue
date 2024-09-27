<script setup lang="ts">
import ExternalAvatar from 'primevue/avatar';
import { ref, computed } from 'vue';
import { useAuthorizationStore } from "@/stores/auth";
import { useI18n } from "vue-i18n";

const props = defineProps({
  avatarPath: {
    type: [String, null],
    default: '',
  },
  placeholderIcon: {
    type: String,
    default: 'pi pi-user',
  },
  isEditing: {
    type: Boolean,
    default: false,
  },
  alt: {
    type: String,
    required: true,
  },
});

const emit = defineEmits(['update:avatarPath']);

const authStore = useAuthorizationStore();
const { t } = useI18n();

const fileInput = ref<HTMLInputElement | null>(null);

const uploadApiURL = computed(() => {
  return process.env.API_BASE_URL + "/upload/image";
});

const triggerFileInput = () => {
  if (props.isEditing) {
    fileInput.value?.click();
  }
};

const uploadFile = async (event: Event) => {
  const target = event.target as HTMLInputElement;
  if (target.files && target.files.length > 0) {
    const file = target.files[0];
    const formData = new FormData();
    formData.append('image', file);

    try {
      const response = await fetch(uploadApiURL.value, {
        method: 'POST',
        headers: {
          'Authorization': 'Bearer ' + authStore.getToken,
        },
        body: formData,
      });

      if (!response.ok) {
        throw new Error('Upload failed');
      }

      const data = await response.json();
      if (data.filename) {
        emit('update:avatarPath', data.filename);
      }
    } catch (error) {
      console.error('Error uploading file:', error);
      // Handle error (e.g., show error message to user)
    }
  }
};

const clearAvatar = () => {
  emit('update:avatarPath', '');
};

const avatarSize = computed(() => {
  return { width: '4rem', height: '4rem' };
});
</script>

<template>
  <div class="relative inline-block group">
    <input
      v-if="isEditing"
      ref="fileInput"
      type="file"
      accept="image/*"
      class="hidden"
      @change="uploadFile"
    />
    <ExternalAvatar
      :image="avatarPath"
      :icon="!avatarPath ? placeholderIcon : undefined"
      size="xlarge"
      :style="avatarSize"
      class="transition-all duration-300 hover:scale-105 cursor-pointer flex items-center justify-center"
      :class="[
        avatarPath ? 'bg-transparent' : 'bg-gray-200 dark:bg-gray-700',
        'text-gray-600 dark:text-gray-200'
      ]"
      shape="circle"
      :alt="alt"
    >
      <i v-if="!avatarPath" :class="[placeholderIcon, 'text-2xl']"></i>
    </ExternalAvatar>
    <div
      v-if="isEditing"
      @click="triggerFileInput"
      class="absolute inset-0 flex items-center justify-center rounded-full bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all duration-300"
      :style="avatarSize"
    >
      <i class="pi pi-camera text-white text-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></i>
    </div>
    <button
      v-if="isEditing && avatarPath"
      class="absolute top-0 right-0 bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center transition-colors duration-200 shadow-md"
      style="transform: translate(25%, -25%);"
      @click="clearAvatar"
    >
      <i class="pi pi-times text-sm"></i>
    </button>
  </div>
</template>