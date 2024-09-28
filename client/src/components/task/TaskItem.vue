<script setup lang="ts">
import { computed, ref, onMounted } from 'vue';
import { useI18n } from 'vue-i18n';
import Checkbox from 'primevue/checkbox';
import InputText from 'primevue/inputtext';
import Datepicker from 'primevue/datepicker';
import Select from 'primevue/select';
import InputNumber from 'primevue/inputnumber';
import Button from 'primevue/button';
import Popover from 'primevue/popover';
import type { Task } from "@/interfaces/Task";

const { t } = useI18n();

const props = defineProps<{
  task: Task;
  clients: { label: string; value: string }[];
  projects: { label: string; value: string }[];
  showAddButtons?: boolean;
}>();

const emit = defineEmits<{
  (e: 'update:task', task: Task): void;
  (e: 'toggleTimeTracking', task: Task): void;
  (e: 'delete:task', taskId: number): void;
  (e: 'add-task-before'): void;
  (e: 'add-task-after'): void;
  (e: 'add-subtask', taskId: number): void;
}>();

const editedTask = ref({ ...props.task });
const isTracking = ref(false);
const nameError = ref('');
const titleInput = ref<InstanceType<typeof InputText> | null>(null);

const isOverdue = computed(() => {
  if (!editedTask.value.dueDate || editedTask.value.completed) return false;
  return new Date(editedTask.value.dueDate) < new Date();
});

const isValid = computed(() => editedTask.value.title && editedTask.value.client);

function toggleComplete() {
  editedTask.value.completed = !editedTask.value.completed;
  emit('update:task', editedTask.value);
}

function formatDate(date: string): string {
  return new Date(date).toLocaleDateString();
}

function formatTime(minutes: number): string {
  const hours = Math.floor(minutes / 60);
  const mins = minutes % 60;
  return `${hours}h ${mins}m`;
}

function formatCurrency(amount: number): string {
  return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(amount);
}

function toggleTimeTracking() {
  isTracking.value = !isTracking.value;
  emit('toggleTimeTracking', editedTask.value);
}

function updateField(field: keyof Task, value: any) {
  editedTask.value[field] = value;
  emit('update:task', editedTask.value);
}

function confirmDeletion() {
  emit('delete:task', editedTask.value.id);
}

function saveTask() {
  if (editedTask.value.title) {
    nameError.value = '';
    emit('update:task', editedTask.value);
  } else {
    nameError.value = t('Name is required');
  }
}

function handleKeyDown(event: KeyboardEvent) {
  if (event.key === 'Enter') {
    saveTask();
    if (titleInput.value) titleInput.value.$el.blur();
  }
}

function handleBlur() {
  saveTask();
}

function addSubtask() {
  emit('add-subtask', editedTask.value.id);
}

onMounted(() => {
  if (!props.task.title && titleInput.value) {
    titleInput.value.$el.focus();
  }
});
</script>

<template>
  <div class="relative">
    <Button v-if="showAddButtons" icon="pi pi-plus" class="absolute -top-6 left-1/2 transform -translate-x-1/2 rounded-full p-button-sm z-10 w-10 h-10" @click="$emit('add-task-before')" />

    <div
      :class="[
        'task-item rounded-lg shadow-sm hover:shadow transition-shadow duration-200 mb-16 p-4 relative',
        { 'border-l-4': true },
        { 'border-yellow-300 bg-white dark:bg-gray-800': editedTask.status === 'Todo' },
        { 'border-orange-300 bg-white dark:bg-gray-800': editedTask.status === 'In Progress' },
        { 'border-green-300 bg-gray-100 dark:bg-gray-700': editedTask.status === 'Completed' },
        { 'border-red-300 bg-white dark:bg-gray-800': editedTask.status === 'Blocked' },
      ]"
    >
      <div class="flex flex-wrap items-center justify-between mb-2">
        <div class="flex items-center flex-grow">
          <Checkbox v-model="editedTask.completed" :binary="true" class="mr-3 flex-shrink-0" @change="toggleComplete" />
          <InputText
            ref="titleInput"
            v-model="editedTask.title"
            class="text-lg font-semibold mr-2 break-words dark:text-white p-0 border-none bg-transparent flex-grow"
            :class="{ 'line-through': editedTask.completed, 'p-invalid': nameError }"
            @blur="handleBlur"
            @keydown="handleKeyDown"
            style="word-break: break-word;"
          />
        </div>
        <div class="flex items-center gap-2 mt-2 sm:mt-0">
          <Button
            :label="isTracking ? 'Pause' : 'Start'"
            :icon="isTracking ? 'pi pi-pause' : 'pi pi-play'"
            :class="['p-button-sm', isTracking ? 'p-button-warning' : 'p-button-success']"
            @click="toggleTimeTracking"
          />
          <Button icon="pi pi-trash" label="Delete" class="p-button-danger p-button-sm" @click="confirmDeletion" />
        </div>
      </div>
      <small v-if="nameError" class="p-error">{{ nameError }}</small>

      <div class="text-sm text-gray-600 dark:text-gray-300 flex flex-wrap gap-2">
        <span
          :class="[
            'cursor-pointer px-2 py-1 rounded-full text-xs font-medium inline-flex items-center',
            { 'bg-yellow-100 text-yellow-800': editedTask.status === 'Todo' },
            { 'bg-blue-100 text-blue-800': editedTask.status === 'In Progress' },
            { 'bg-green-100 text-green-800': editedTask.status === 'Completed' },
            { 'bg-red-100 text-red-800': editedTask.status === 'Blocked' },
          ]"
          @click="(event) => $refs.statusPopover.toggle(event)"
        >
          {{ editedTask.status }}
        </span>
        <Popover ref="statusPopover" appendTo="body">
          <Select
            v-model="editedTask.status"
            :options="['Todo', 'In Progress', 'Completed', 'Blocked']"
            @change="updateField('status', editedTask.status)"
          />
        </Popover>

        <span class="cursor-pointer flex items-center" @click="(event) => $refs.clientPopover.toggle(event)">
          <i class="pi pi-user mr-1"></i>{{ editedTask.client || 'Select Client' }}
        </span>
        <Popover ref="clientPopover" appendTo="body">
          <Select
            v-model="editedTask.client"
            :options="clients"
            optionLabel="label"
            optionValue="value"
            @change="updateField('client', editedTask.client)"
          />
        </Popover>

        <span class="cursor-pointer flex items-center" @click="(event) => $refs.projectPopover.toggle(event)">
          <i class="pi pi-folder mr-1"></i>{{ editedTask.project || 'Select Project' }}
        </span>
        <Popover ref="projectPopover" appendTo="body">
          <Select
            v-model="editedTask.project"
            :options="projects"
            optionLabel="label"
            optionValue="value"
            @change="updateField('project', editedTask.project)"
          />
        </Popover>

        <span :class="['cursor-pointer flex items-center', { 'text-red-500': isOverdue }]" @click="(event) => $refs.dueDatePopover.toggle(event)">
          <i class="pi pi-calendar-times mr-1"></i>{{ editedTask.dueDate ? formatDate(editedTask.dueDate) : 'Set Due Date' }}
        </span>
        <Popover ref="dueDatePopover" appendTo="body">
          <Datepicker v-model="editedTask.dueDate" @date-select="updateField('dueDate', editedTask.dueDate)" />
        </Popover>

        <span class="cursor-pointer flex items-center" @click="(event) => $refs.scheduledDatePopover.toggle(event)">
          <i class="pi pi-calendar mr-1"></i>{{ editedTask.scheduledDate ? formatDate(editedTask.scheduledDate) : 'Set Schedule' }}
        </span>
        <Popover ref="scheduledDatePopover" appendTo="body">
          <Datepicker v-model="editedTask.scheduledDate" @date-select="updateField('scheduledDate', editedTask.scheduledDate)" />
        </Popover>

        <span class="cursor-pointer flex items-center" @click="(event) => $refs.timeEstimatePopover.toggle(event)">
          <i class="pi pi-clock mr-1"></i>{{ editedTask.timeEstimate ? formatTime(editedTask.timeEstimate) : 'Set Estimate' }}
        </span>
        <Popover ref="timeEstimatePopover" appendTo="body">
          <InputNumber
            v-model="editedTask.timeEstimate"
            placeholder="Minutes"
            @blur="updateField('timeEstimate', editedTask.timeEstimate)"
          />
        </Popover>

        <span v-if="editedTask.trackedTime" class="flex items-center">
          <i class="pi pi-stopwatch mr-1"></i>Tracked: {{ formatTime(editedTask.trackedTime) }}
        </span>

        <span class="cursor-pointer flex items-center" @click="(event) => $refs.revenuePopover.toggle(event)">
          <i class="pi pi-dollar mr-1"></i>{{ editedTask.estimatedRevenue ? formatCurrency(editedTask.estimatedRevenue) : 'Set Revenue' }}
        </span>
        <Popover ref="revenuePopover" appendTo="body">
          <InputNumber
            v-model="editedTask.estimatedRevenue"
            mode="currency"
            currency="USD"
            locale="en-US"
            @blur="updateField('estimatedRevenue', editedTask.estimatedRevenue)"
          />
        </Popover>
      </div>

      <div v-if="editedTask.subtasks && editedTask.subtasks.length > 0" class="pl-4 pr-4 pb-4 mt-4 border-t border-gray-100 dark:border-gray-700">
        <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-2 mt-2">Subtasks:</h4>
        <TaskItem
          v-for="subtask in editedTask.subtasks"
          :key="subtask.id"
          :task="subtask"
          :clients="clients"
          :projects="projects"
          :show-add-buttons="showAddButtons"
          class="mt-2"
          @update:task="(updatedSubtask) => updateField('subtasks', editedTask.subtasks!.map(st => st.id === updatedSubtask.id ? updatedSubtask : st))"
          @delete:task="(deletedSubtaskId) => updateField('subtasks', editedTask.subtasks!.filter(st => st.id !== deletedSubtaskId))"
          @add-subtask="$emit('add-subtask', $event)"
        />
      </div>

      <Button v-if="showAddButtons" icon="pi pi-plus" label="Add Subtask" class="mt-2 p-button-sm p-button-text" @click="addSubtask" />
    </div>

    <Button v-if="showAddButtons" icon="pi pi-plus" class="absolute -bottom-6 left-1/2 transform -translate-x-1/2 rounded-full p-button-sm z-10 w-10 h-10" @click="$emit('add-task-after')" />
  </div>
</template>