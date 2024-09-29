<script setup lang="ts">
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';
import TaskItem from "@/components/task/TaskItem.vue";
import type { Task } from "@/interfaces/Task";
import PageHeader from "@/components/page/PageHeader.vue";
import { useToast } from "primevue/usetoast";
import { useConfirm } from "primevue/useconfirm";

const { t } = useI18n();
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
  // ... other tasks
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

function addTaskAtPosition(position: number) {
  const newTask: Task = {
    id: Date.now(),
    title: '',
    client: 'Internal',
    status: 'Todo',
    completed: false,
    isNewTask: true,
    subtasks: []
  };
  tasks.value.splice(position, 0, newTask);
}

function saveTask(updatedTask: Task) {
  const index = tasks.value.findIndex(t => t.id === updatedTask.id);
  if (index !== -1 && updatedTask.title.trim()) {
    tasks.value[index] = { ...updatedTask, isNewTask: false };
    toast.add({ severity: 'success', summary: t('Saved'), detail: t('Task saved'), life: 3000 });
  } else if (index !== -1) {
    tasks.value.splice(index, 1);
    toast.add({ severity: 'info', summary: t('Discarded'), detail: t('Empty task discarded'), life: 3000 });
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

function addSubtask({ parentId, position }: { parentId: number, position: number }) {
  console.log('addSubtask called in TasksPage', { parentId, position });
  const newSubtask: Task = {
    id: Date.now(),
    title: '',
    client: 'Internal',
    status: 'Todo',
    completed: false,
    isNewTask: true,
    subtasks: []
  };

  function addSubtaskRecursive(tasks: Task[]): boolean {
    for (let i = 0; i < tasks.length; i++) {
      if (tasks[i].id === parentId) {
        console.log('Parent task found', tasks[i]);
        if (!tasks[i].subtasks) {
          tasks[i].subtasks = [];
        }
        tasks[i].subtasks.splice(position, 0, newSubtask);
        console.log('Subtask added', newSubtask);
        return true;
      }
      if (tasks[i].subtasks && addSubtaskRecursive(tasks[i].subtasks)) {
        return true;
      }
    }
    return false;
  }

  const updatedTasks = JSON.parse(JSON.stringify(tasks.value));
  if (addSubtaskRecursive(updatedTasks)) {
    console.log('Tasks updated', updatedTasks);
    tasks.value = updatedTasks;
  } else {
    console.error('Parent task not found');
  }
}
</script>

<template>
  <PageHeader title="Tasks" icon="pi-check-circle" />
  <div class="flex container flex-wrap gap-8 mb-8">
    <div class="tasks-list w-full">
      <TaskItem
        v-for="(task, index) in tasks"
        :key="task.id"
        :task="task"
        :clients="clients"
        :projects="projects"
        :show-add-buttons="true"
        :is-new-task="task.isNewTask"
        @update:task="saveTask"
        @delete:task="deleteTask"
        @add-task-before="addTaskAtPosition(index)"
        @add-task-after="addTaskAtPosition(index + 1)"
        @add-subtask="addSubtask"
      />
    </div>
  </div>
</template>