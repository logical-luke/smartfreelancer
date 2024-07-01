<script>
import EditClientForm from "@/components/client/EditClientForm.vue";

export default {
  name: "EditClientPage",
  components: {EditClientForm}
}
</script>

<template>
  <div class="flex mb-8">
    <div>
      <h3 class="text-xl font-bold">{{ $t("Edit Client") }}</h3>
    </div>
  </div>
  <div class="flex container">
    <div class="flex flex-col gap-4 w-full">
      <edit-client-form />
    </div>
  </div>
</template>

