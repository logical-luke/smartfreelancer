<script setup lang="ts">
import { useI18n } from 'vue-i18n';
const { t } = useI18n();

import { ref, computed } from 'vue';
import TaskItem from "@/components/task/TaskItem.vue";
import type Task from "@/interfaces/task";
import PageHeader from "@/components/page/PageHeader.vue";
import AddItemFloatingButton from "@/components/navigation/AddItemFloatingButton.vue";
import { useToast } from "primevue/usetoast";
import { useConfirm } from "primevue/useconfirm";
import Button from 'primevue/button';

const toast = useToast();
const confirm = useConfirm();

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


const clients = ref([
  { label: 'TechCorp', value: 'TechCorp' },
  { label: 'FinTech Inc', value: 'FinTech Inc' },
  { label: 'Internal', value: 'Internal' },
]);

const projects = ref([
  { label: 'Website Redesign', value: 'Website Redesign' },
  { label: 'Mobile App', value: 'Mobile App' },
]);

function addTaskAtPosition(position: number, parentId: number | null = null) {
  const newTask: Task = {
    id: Date.now(),
    title: '',
    client: 'Internal',
    status: 'Todo',
    completed: false,
    parentId: parentId
  };
  if (parentId) {
    const parentIndex = tasks.value.findIndex(t => t.id === parentId);
    if (parentIndex !== -1) {
      if (!tasks.value[parentIndex].subtasks) {
        tasks.value[parentIndex].subtasks = [];
      }
      tasks.value[parentIndex].subtasks!.splice(position, 0, newTask);
    }
  } else {
    tasks.value.splice(position, 0, newTask);
  }
}

function saveTask(task: Task) {
  const index = tasks.value.findIndex(t => t.id === task.id);
  if (index !== -1) {
    tasks.value[index] = task;
    toast.add({ severity: 'success', summary: t('Saved'), detail: t('Task saved'), life: 3000 });
  }
}

function deleteTask(taskId: number) {
  confirm.require({
    message: t('Are you sure you want to delete this task?'),
    header: t('Confirm Deletion'),
    icon: 'pi pi-exclamation-triangle',
    accept: () => {
      tasks.value = tasks.value.filter(t => t.id !== taskId);
      toast.add({ severity: 'error', summary: t('Deleted'), detail: t('Task deleted'), life: 3000 });
    }
  });
}

function addSubtask(parentId: number) {
  const newTask: Task = {
    id: Date.now(),
    title: '',
    client: 'Internal',
    status: 'Todo',
    completed: false,
    parentId: parentId
  };
  const parentIndex = tasks.value.findIndex(t => t.id === parentId);
  if (parentIndex !== -1) {
    if (!tasks.value[parentIndex].subtasks) {
      tasks.value[parentIndex].subtasks = [];
    }
    tasks.value[parentIndex].subtasks!.push(newTask);
  }
}

const sortedTasks = computed(() => {
  return [...tasks.value].sort((a, b) => b.id - a.id);
});
</script>

<template>
  <PageHeader title="Tasks" icon="pi-check-circle" />
  <div class="flex container flex-wrap gap-8 mb-8">
    <div class="tasks-list w-full">
      <transition-group name="slide">
        <template v-for="(task, index) in sortedTasks" :key="task.id">
          <TaskItem
            :task="task"
            :clients="clients"
            :projects="projects"
            :show-add-buttons="true"
            @update:task="saveTask"
            @delete:task="deleteTask"
            @add-task-before="addTaskAtPosition(index)"
            @add-task-after="addTaskAtPosition(index + 1)"
            @add-subtask="addSubtask(task.id)"
          />
        </template>
      </transition-group>
    </div>
  </div>
</template>