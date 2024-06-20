<template>
  <div class="flex mb-8">
    <div>
      <h3 class="text-xl font-bold">{{ $t("Add Client") }}</h3>
    </div>
  </div>
  <div class="flex container">
    <div class="flex flex-col gap-4 w-full">
      <client-form />
    </div>
  </div>
</template>

<script>
import ClientForm from "@/components/client/ClientForm.vue";

export default {
  name: "AddClientPage",
  components: {
    ClientForm,
  },
  data() {
    return {
      clientsPageRoute: {
        name: "ClientsPage"
      }
    }
  },
};
</script>

<style scoped></style>
