<script setup lang="ts">
import PageHeader from "@/components/page/PageHeader.vue";
import QalendarView from "@/components/calendar/QalendarView.vue";
</script>

<template>
  <PageHeader title="Calendar" icon="pi-calendar"/>
  <div class="flex container">
    <QalendarView/>
  </div>
</template>