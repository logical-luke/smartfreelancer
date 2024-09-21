<script setup lang="ts">
import { defineProps } from 'vue';
import ActionButton from "@/components/form/ActionButton.vue";

const props = defineProps<{
  id: string;
  name: string;
  description: string | null;
  client: string | null;
  ongoingTasks: number;
  finishedTasks: number;
  blockedTasks: number;
  notStartedTasks: number;
}>();

const totalTasks = props.ongoingTasks + props.finishedTasks + props.blockedTasks + props.notStartedTasks;
const progress = totalTasks > 0 ? (props.finishedTasks / totalTasks) * 100 : 0;
</script>

<template>
  <div class="p-6 bg-white shadow rounded-lg">
    <div class="flex justify-between items-center mb-4">
      <h3 class="text-xl font-bold">{{ name }}</h3>
    </div>
    <p class="text-gray-700 mb-4">{{ description }}</p>
    <div class="flex justify-between items-center mb-4">
      <div>
        <p v-if="client" class="text-sm text-gray-500">{{ $t("Client") }}: {{ client }}</p>
      </div>
    </div>
    <div class="mb-4">
      <div class="h-2 bg-gray-200 rounded">
        <div class="h-2 bg-blue-500 rounded" :style="{ width: progress + '%' }"></div>
      </div>
      <p class="text-sm text-gray-500 mt-2">{{ progress.toFixed(2) }}% {{ $t("Completed") }}</p>
    </div>
    <div class="flex flex-col md:flex-row gap-4 mb-4">
      <div class="p-4 bg-gray-100 rounded w-full md:w-1/4">
        <p class="text-xs font-medium">{{ $t("Ongoing Tasks") }}</p>
        <span>{{ ongoingTasks }}</span>
      </div>
      <div class="p-4 bg-gray-100 rounded w-full md:w-1/4">
        <p class="text-xs font-medium">{{ $t("Finished Tasks") }}</p>
        <span>{{ finishedTasks }}</span>
      </div>
      <div class="p-4 bg-gray-100 rounded w-full md:w-1/4">
        <p class="text-xs font-medium">{{ $t("Blocked Tasks") }}</p>
        <span>{{ blockedTasks }}</span>
      </div>
      <div class="p-4 bg-gray-100 rounded w-full md:w-1/4">
        <p class="text-xs font-medium">{{ $t("Not Started Tasks") }}</p>
        <span>{{ notStartedTasks }}</span>
      </div>
    </div>
    <div class="flex gap-2">
      <ActionButton>{{ $t("Edit") }}</ActionButton>
      <ActionButton>{{ $t("Delete") }}</ActionButton>
    </div>
  </div>
</template>

<style scoped>
</style>
