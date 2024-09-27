<script setup lang="ts">
import ExternalAvatar from 'primevue/avatar';
import ExternalAvatarGroup from "primevue/avatargroup";

import {ref} from 'vue';

const props = defineProps({
  avatarPath: {
    type: String,
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

const fileInput = ref<HTMLInputElement | null>(null);

const triggerFileInput = () => {
  if (props.isEditing) {
    fileInput.value?.click();
  }
};

const uploadFile = (event: Event) => {
  const target = event.target as HTMLInputElement;
  if (target.files && target.files.length > 0) {
    const file = target.files[0];
    const reader = new FileReader();
    reader.onload = (e) => {
      emit('update:avatarPath', e.target?.result as string);
    };
    reader.readAsDataURL(file);
  }
};

const clearAvatar = () => {
  emit('update:avatarPath', '');
};
</script>

<template>
  <div class="relative group custom-avatar">
    <input
      v-if="isEditing"
      ref="fileInput"
      type="file"
      accept="image/*"
      class="hidden"
      @change="uploadFile"
    />
    <ExternalAvatarGroup @click="triggerFileInput" class="custom-avatar-group">
      <ExternalAvatar
        :image="avatarPath"
        :icon="!avatarPath ? placeholderIcon : undefined"
        size="xlarge"
        class="mr-2 transition-transform duration-300 hover:scale-105 cursor-pointer"
        shape="circle"
        :alt="alt"
        :style="{ color: 'white', borderColor: 'white' }"
      />
      <div
        v-if="isEditing"
        class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-full"
        :style="{ width: '4rem', height: '4rem' }"
      >
        <i class="pi pi-camera text-white text-2xl"></i>
      </div>
    </ExternalAvatarGroup>
    <button
      v-if="isEditing && avatarPath"
      @click="clearAvatar"
      class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 dark:bg-red-700 dark:hover:bg-red-800 text-white rounded-full w-6 h-6 flex items-center justify-center transition-colors duration-200 border border-white"
      style="transform: translate(50%, -50%);"
    >
      <i class="pi pi-times text-sm"></i>
    </button>
  </div>
</template>

<style scoped>
.custom-avatar-group :deep(.p-avatar-group) {
  @apply border-white;
}

.custom-avatar :deep(.p-avatar) {
  @apply border-white;
}
</style>