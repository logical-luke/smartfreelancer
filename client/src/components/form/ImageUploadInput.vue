<script setup lang="ts">
import { computed } from "vue";
import FileUpload, {type FileUploadBeforeSendEvent, type FileUploadUploadEvent} from "primevue/fileupload";
import { useAuthorizationStore } from "@/stores/auth";
import { useI18n } from "vue-i18n";

const authStore = useAuthorizationStore();
const { t } = useI18n();
const emit = defineEmits(["file-uploaded"]);

const uploadApiURL = computed(() => {
  return process.env.API_BASE_URL + "/upload/image";
});

const chooseLabel = computed(() => {
  return t("Browse");
});

const beforeSend = (event: FileUploadBeforeSendEvent) => {
  const token = authStore.getToken;
  event.xhr.setRequestHeader("Authorization", "Bearer " + token);

  return event;
};

const onUpload = (event: FileUploadUploadEvent) => {
  const response = JSON.parse(event.xhr.response);
  if (response.filename) {
    emit("file-uploaded", response.filename);
  }
};
</script>

<template>
  <FileUpload
    mode="basic"
    class="inline-flex shadow p-4 min-w-40 justify-center flex-nowrap items-center text-sm font-medium bg-white border-2 border-indigo-500 text-black hover:bg-indigo-500 hover:text-white rounded-md disabled:bg-slate-50 disabled:text-slate-500 transition duration-200"
    name="image"
    :url="uploadApiURL"
    accept="image/*"
    :max-file-size="1000000"
    :auto="true"
    :choose-label="chooseLabel"
    @upload="onUpload"
    @before-send="beforeSend"
  />
</template>
