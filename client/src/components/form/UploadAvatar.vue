<script setup lang="ts">
import { ref, computed } from 'vue';
import Avatar from 'primevue/avatar';
import AvatarGroup from 'primevue/avatargroup';
import { useAuthorizationStore } from "@/stores/auth";

const authStore = useAuthorizationStore();

const emit = defineEmits(['update:avatar']);

const uploadApiURL = computed(() => {
  return process.env.API_BASE_URL + "/upload/image";
});

const fileInput = ref<HTMLInputElement | null>(null);

const uploadFile = async (event: Event) => {
  const target = event.target as HTMLInputElement;
  if (!target.files?.length) return;

  const file = target.files[0];
  if (file.size > 1000000) {
    console.error('File size exceeds 1MB limit');
    return;
  }

  const formData = new FormData();
  formData.append('image', file);  // Changed 'file' to 'image'

  try {
    const response = await fetch(uploadApiURL.value, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${authStore.getToken}`,
      },
      body: formData,
    });

    if (!response.ok) {
      const errorData = await response.json();
      throw new Error(errorData.error || 'Upload failed');
    }

    const data = await response.json();
    if (data.filename) {
      emit('update:avatar', data.filename);
    }
  } catch (error) {
    console.error('Error uploading file:', error);
  }
};

const triggerFileInput = () => {
  fileInput.value?.click();
};
</script>

<template>
  <div class="relative group">
    <input
        ref="fileInput"
        type="file"
        accept="image/*"
        class="hidden"
        @change="uploadFile"
    />
    <AvatarGroup @click="triggerFileInput">
      <Avatar
          icon="pi pi-user"
          size="xlarge"
          class="mr-2 transition-transform duration-300 hover:scale-105 cursor-pointer"
          shape="circle"
      />
    </AvatarGroup>
  </div>
</template>
