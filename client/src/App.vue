<script setup>
import { RouterLink, RouterView } from "vue-router";
import ProjectsView from "@/components/Projects.vue";
import Sidebar from "@/components/Sidebar.vue";
</script>

<template>
  <div class="">

    <div>
      <Sidebar />
      <ProjectsView />
    </div>
  </div>
</template>

