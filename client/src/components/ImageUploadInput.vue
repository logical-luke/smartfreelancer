<script>
import FileUpload from "primevue/fileupload";
import store from "@/store";

export default {
  name: "ImageUploadInput",
  components: { FileUpload },
  emits: ["file-uploaded"],
  computed: {
    uploadApiURL() {
      return process.env.API_BASE_URL + "/upload/image";
    },
    chooseLabel() {
      return this.$t("Browse");
    },
  },
  methods: {
    beforeSend(request) {
      request.xhr.setRequestHeader(
        "Authorization",
        "Bearer " + store.getters["authorization/getToken"]
      );

      return request;
    },
    onUpload(event) {
      const response = JSON.parse(event.xhr.response);

      if (response.filename) {
        this.$emit("file-uploaded", response.filename);
      }
    },
  },
};
</script>

<template>
  <file-upload
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
