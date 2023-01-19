<script setup>
import Sidebar from "@/components/Sidebar.vue";
</script>

<template>
  <div v-if="this.$route.name !== 'Login'" class="mx-auto lg:ml-80">
    <Sidebar />
    <router-view />
  </div>
  <div v-else>
    <router-view />
  </div>
</template>
