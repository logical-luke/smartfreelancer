<script setup lang="ts">
import { ref, computed, nextTick, onMounted, watch } from 'vue';
import { useI18n } from 'vue-i18n';
import { useProjectsStore } from '@/stores/projects';
import { useClientsStore } from '@/stores/clients';
import InputText from 'primevue/inputtext';
import DatePicker from 'primevue/datepicker';
import Select from 'primevue/select';
import DestructiveActionButton from "@/components/form/DestructiveActionButton.vue";
import TaskStatusCard from '@/components/task/TaskStatusCard.vue';
import ProgressBar from 'primevue/progressbar';
import UploadAvatar from '@/components/form/UploadAvatar.vue';
import { defineProps, defineEmits } from 'vue';
import type Project from '@/interfaces/project';
import PrimaryActionButton from "@/components/form/PrimaryActionButton.vue";
import SecondaryActionButton from "@/components/form/SecondaryActionButton.vue";

const props = defineProps<{
  project: Project;
  isDraft: boolean;
}>();

const { t } = useI18n();
const projectsStore = useProjectsStore();
const clientsStore = useClientsStore();
const emit = defineEmits(['save', 'discard']);

const isEditing = ref(props.isDraft);
const project = ref({ ...props.project });

const isValid = computed(() => {
  return project.value.name && project.value.client;
});

const focusNameInput = async () => {
  await nextTick();
  const nameInput = document.getElementById('editNameInput');
  if (nameInput) nameInput.focus();
};

onMounted(() => {
  if (props.isDraft) focusNameInput();
});

const saveProject = async () => {
  if (isValid.value) {
    const projectPayload = {
      ...project.value,
      clientId: project.value.client.id,
    };
    delete projectPayload.client;
    delete projectPayload.timeWorked;
    delete projectPayload.internal;

    if (props.isDraft) {
      await projectsStore.create(projectPayload);
      emit('save');
    } else {
      await projectsStore.update(project.value.id, projectPayload);
      isEditing.value = false;
    }
  }
};

const discardProject = () => {
  project.value = { ...props.project };
  isEditing.value = false;
  emit('discard');
};

const confirmDeletion = async () => {
  await projectsStore.delete(project.value.id);
};

const updateAvatar = (avatar: string) => {
  project.value.avatar = avatar;
};

watch(() => props.project, (newProject) => {
  project.value = { ...newProject };
});

onMounted(() => {
  clientsStore.load();
});

const totalTasks = computed(() => project.value.todoTasks + project.value.inProgressTasks + project.value.blockedTasks + project.value.completedTasks);
const progress = computed(() => totalTasks.value > 0 ? (project.value.completedTasks / totalTasks.value) * 100 : 0);
</script>
<template>
  <div class="w-full bg-white shadow rounded-lg overflow-hidden transition-all duration-300 hover:shadow">
    <div class="bg-gradient-to-r from-indigo-400 to-indigo-600 p-4">
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 sm:gap-4">
        <div class="flex items-center">
          <UploadAvatar v-if="isEditing" :avatar="project.avatar" @update:avatar="updateAvatar" placeholder-icon="pi pi-folder" />
          <h3 class="text-xl sm:text-2xl font-bold text-white flex items-center ml-4">
            <span v-if="!isEditing" class="truncate max-w-[200px] sm:max-w-[300px]">{{ project.name }}</span>
            <InputText v-else id="editNameInput" v-model="project.name" placeholder="Project Name" class="w-full sm:w-auto" />
          </h3>
        </div>
        <span v-if="!isEditing" class="text-sm font-medium px-3 py-1 bg-white bg-opacity-20 text-white rounded-full whitespace-nowrap">
          {{ project.client }}
        </span>
        <Select v-else v-model="project.client" :options="clientsStore.clients" optionLabel="name" placeholder="Select Client" class="w-full sm:w-auto" />
      </div>
    </div>

    <div class="p-4 sm:p-6">
      <p v-if="!isEditing" class="text-gray-700 mb-6">{{ project.description }}</p>
      <InputText v-else v-model="project.description" placeholder="Project Description" class="w-full mb-6" />

      <div v-if="!isEditing" class="mb-6">
        <div class="flex justify-between items-center mb-2">
          <span class="text-sm font-medium text-gray-600">{{ t("Progress") }}</span>
          <span class="text-sm fonPrimaryActionButtont-bold text-indigo-600">{{ progress.toFixed(2) }}%</span>
        </div>
        <ProgressBar :value="progress" />
      </div>
      <DatePicker v-else v-model="project.dueDate" placeholder="Due Date" class="w-full mb-6" />

      <div v-if="!isEditing" class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <TaskStatusCard :count="project.inProgressTasks" :label="t('In Progress')" icon="pi-spin pi-spinner" color="orange" />
        <TaskStatusCard :count="project.todoTasks" :label="t('Todo')" icon="pi-list" color="yellow" />
        <TaskStatusCard :count="project.blockedTasks" :label="t('Blocked')" icon="pi-ban" color="red" />
        <TaskStatusCard :count="project.completedTasks" :label="t('Completed')" icon="pi-check-circle" color="green" />
      </div>

      <div class="flex flex-col gap-4">
        <div v-if="!isEditing" class="flex items-center">
          <span  class="text-sm text-gray-600">{{ project.dueDate }}</span>
        </div>
        <div class="flex flex-col sm:flex-row gap-2 w-full">
          <SecondaryActionButton v-if="!isEditing" @click="isEditing = true">
            <i class="pi pi-pencil mr-2"></i>
            {{ t("Edit") }}
          </SecondaryActionButton>
          <DestructiveActionButton v-if="!isEditing" @click="confirmDeletion">
            <i class="pi pi-trash mr-2"></i>
            {{ t("Delete") }}
          </DestructiveActionButton>
          <PrimaryActionButton v-else @click="saveProject" :disabled="!isValid">
            <i class="pi pi-check mr-2"></i>
            {{ t("Save Project") }}
          </PrimaryActionButton>
          <SecondaryActionButton v-if="isEditing" @click="discardProject">
            <i class="pi pi-times mr-2"></i>
            {{ t("Discard") }}
          </SecondaryActionButton>
        </div>
      </div>
    </div>
  </div>
</template>
