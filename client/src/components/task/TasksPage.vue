<template>
  <div class="mx-auto lg:ml-80">
    <NewButton go-to="/task/create">Task</NewButton>
  </div>
</template>

<script>
import NewButton from "@/components/ui/NewButton.vue";

export default {
  name: "Tasks",
  components: { NewButton },
};
</script>

<style scoped></style>
