<script setup>
import { RouterLink, RouterView } from "vue-router";
import ProjectsView from "@/components/Projects.vue";
import Sidebar from "@/components/Sidebar.vue";
</script>

<template>
    <div>
      <Sidebar />
      <ProjectsView />
    </div>
</template>

