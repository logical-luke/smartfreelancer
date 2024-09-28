<script setup lang="ts">
import { useI18n } from 'vue-i18n';
const { t } = useI18n();

import {ref} from 'vue';
import TaskItem from "@/components/task/TaskItem.vue";
import type Task from "@/interfaces/task";
import PageHeader from "@/components/page/PageHeader.vue";

const tasks = ref<Task[]>([
  {
    id: 1,
    title: 'Design new landing page',
    completed: false,
    dueDate: '2023-06-15',
    project: 'Website Redesign',
    client: 'TechCorp',
    timeEstimate: 480,
    trackedTime: 300,
    status: 'In Progress',
    estimatedRevenue: 2000,
    subtasks: [
      {
        id: 11,
        title: 'Create wireframes',
        completed: true,
        client: 'TechCorp',
        timeEstimate: 120,
        status: 'Completed',
        trackedTime: 90,
        subtasks: [
          {
            id: 111,
            title: 'Create wireframes',
            completed: true,
            client: 'TechCorp',
            timeEstimate: 120,
            trackedTime: 90,
            status: 'Completed',
          },
          {
            id: 121,
            title: 'Design mockups',
            completed: false,
            client: 'TechCorp',
            timeEstimate: 240,
            trackedTime: 180,
            status: 'Blocked',
          }
        ]

      },
      {
        id: 12,
        title: 'Design mockups',
        completed: false,
        client: 'TechCorp',
        timeEstimate: 240,
        trackedTime: 180,
        status: "In Progress",
      }
    ]
  },
  {
    id: 2,
    title: 'Implement user authentication',
    completed: false,
    dueDate: '2023-06-20',
    scheduledDate: '2023-06-20',
    project: 'Mobile App',
    client: 'FinTech Inc',
    timeEstimate: 360,
    trackedTime: 120,
    estimatedRevenue: 1500,
    status: 'Todo'
  },
  {
    id: 3,
    title: 'Write documentation',
    completed: true,
    dueDate: '2023-06-10',
    client: 'Internal',
    timeEstimate: 240,
    trackedTime: 270,
    status: 'Completed'
  },
  {
    id: 4,
    title: 'Prepare for launch',
    completed: false,
    dueDate: '2025-06-25',
    project: 'Website Redesign',
    client: 'TechCorp',
    timeEstimate: 120,
    trackedTime: 0,
    status: 'Todo'
  },
  {
    id: 5,
    title: 'Review designs',
    completed: false,
    dueDate: '2023-06-18',
    project: 'Website Redesign',
    client: 'TechCorp',
    timeEstimate: 120,
    trackedTime: 0,
    status: 'Todo'
  }
]);

function updateTask(updatedTask: Task) {
  const index = tasks.value.findIndex(t => t.id === updatedTask.id);
  if (index !== -1) {
    tasks.value[index] = updatedTask;
  }
}
</script>

<template>
  <PageHeader title="Tasks" icon="pi-check-circle" />
  <div class="flex container flex-wrap gap-8 mb-8">
    <div class="tasks-list w-full">
      <TaskItem
          v-for="task in tasks"
          :key="task.id"
          :task="task"
          @update:task="updateTask"
      />
    </div>
  </div>
</template>
