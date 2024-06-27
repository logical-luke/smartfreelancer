<template>
  <div class="flex mb-8">
    <div>
      <h3 class="text-xl font-bold">{{ $t("Add Client") }}</h3>
    </div>
  </div>
  <div class="flex container">
    <div class="flex flex-col gap-4 w-full">
      <add-client-form />
    </div>
  </div>
</template>

<script>
import AddClientForm from "@/components/client/AddClientForm.vue";

export default {
  name: "AddClientPage",
  components: {
    AddClientForm,
  },
};
</script>

<style scoped></style>
