<script setup lang="ts">
import PageHeader from "@/components/page/PageHeader.vue";
import SectionWrapper from "@/components/SectionWrapper.vue";
import QalendarView from "@/components/calendar/QalendarView.vue";
import { ref } from 'vue';
import Button from 'primevue/button';

const currentView = ref('month');

function changeView(view: string) {
  currentView.value = view;
}

function addEvent() {
  // Implement add event logic here
  console.log('Adding new event');
}
</script>

<template>
  <PageHeader title="Calendar" icon="pi pi-calendar"/>
  <div class="container mt-4">
    <SectionWrapper title="Calendar View" icon="pi pi-calendar" color="indigo">
      <div class="mb-4 flex justify-between items-center">
        <div>
          <Button @click="changeView('day')" class="p-button-sm mr-2" :class="{ 'p-button-outlined': currentView !== 'day' }">Day</Button>
          <Button @click="changeView('week')" class="p-button-sm mr-2" :class="{ 'p-button-outlined': currentView !== 'week' }">Week</Button>
          <Button @click="changeView('month')" class="p-button-sm" :class="{ 'p-button-outlined': currentView !== 'month' }">Month</Button>
        </div>
        <Button @click="addEvent" icon="pi pi-plus" label="Add Event" class="p-button-sm" />
      </div>
      <QalendarView :view="currentView" />
    </SectionWrapper>
  </div>
</template>