<template>
  <div class="mx-auto lg:ml-80">
    <NewButton go-to="/client/create">Client</NewButton>
  </div>
</template>

<script>
import NewButton from "@/components/ui/NewButton.vue";

export default {
  name: "ClientsPage",
  components: { NewButton }
};
</script>

<style scoped>

</style>
