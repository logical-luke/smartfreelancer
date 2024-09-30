<script setup lang="ts">
import {useI18n} from 'vue-i18n';

const {t} = useI18n();
import {ref, computed, nextTick, onMounted, watch} from 'vue';
import {useProjectsStore} from '@/stores/projects';
import {useClientsStore} from '@/stores/clients';
import InputText from 'primevue/inputtext';
import DatePicker from 'primevue/datepicker';
import Select from 'primevue/select';
import Avatar from '@/components/form/Avatar.vue';
import {defineProps, defineEmits} from 'vue';
import type {Project, ProjectForm} from "@/interfaces/Project";
import Tag from 'primevue/tag';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import Textarea from 'primevue/textarea';
import TaskOverviewGrid from "@/components/report/TaskOverviewGrid.vue";
import TimeOverviewGrid from "@/components/report/TimeOverviewGrid.vue";
import RevenueOverviewGrid from "@/components/report/RevenueOverviewGrid.vue";
import projectToProjectForm from "@/services/mappers/projectToProjectForm";
import Card from 'primevue/card';

import {useToast} from "primevue/usetoast";
import ActionButton from "@/components/form/ActionButton.vue";

const props = defineProps<{
  project: Project;
  isDraft: boolean;
}>();
const toast = useToast();
const projectsStore = useProjectsStore();
const clientsStore = useClientsStore();
const emit = defineEmits(['save', 'discard']);

const isEditing = ref(props.isDraft);
const project = ref({...props.project});

const nameError = ref('');
const clientError = ref('');

const isNameInvalid = computed(() => nameError.value !== '');
const isClientInvalid = computed(() => clientError.value !== '');

const isValid = computed(() => {
  return project.value.name && project.value.clientId && !nameError.value && !clientError.value;
});

onMounted(() => {
  clientsStore.load();
});

watch(() => clientsStore.clients, (newClients) => {
  if (project.value.clientId) {
    const client = newClients.find(client => client.id === project.value.clientId);
    if (client) {
      project.value.clientId = client.id;
    }
  }
});

const validateName = () => {
  if (!project.value.name || project.value.name === '') {
    nameError.value = t('Name cannot be empty');
  } else {
    nameError.value = '';
  }
};

const revalidateName = () => {
  if (nameError.value && nameError.value !== '') {
    validateName();
  }
};

const validateClient = () => {
  if (!project.value.clientId || project.value.clientId === '') {
    clientError.value = t('Client must be selected');
  } else {
    clientError.value = '';
  }
};

const revalidateClient = () => {
  if (clientError.value && clientError.value !== '') {
    validateClient();
  }
};

const saveProject = async () => {
  validateName();
  validateClient();
  if (isValid.value) {
    const projectPayload = projectToProjectForm(project.value);
    if (props.isDraft) {
      await projectsStore.create(projectPayload);
      toast.add({severity: 'success', summary: t('Created'), detail: t('Project created'), life: 3000});
    } else {
      await projectsStore.update(project.value.id, projectPayload);
      toast.add({severity: 'success', summary: t('Saved'), detail: t('Project saved'), life: 3000});
    }
    emit('save');
    isEditing.value = false;
  }
};

const discardProject = () => {
  project.value = {...props.project};
  isEditing.value = false;
  emit('discard');
};

const confirmDeletion = async () => {
  await projectsStore.delete(project.value.id);
};

watch(() => props.project, (newProject) => {
  project.value = {...newProject};
  if (project.value.clientId) {
    const client = clientsStore.clients.find(client => client.id === project.value.clientId);
    if (client) {
      project.value.clientId = client.id;
    }
  }
});

const selectedClient = computed(() => {
  return clientsStore.clients.find(client => client.id === project.value.clientId);
});
</script>

<template>
  <Card
      class="w-full bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden transition-all duration-300 hover:shadow-md border border-gray-200 dark:border-gray-700">
    <template #title>
      <div class="p-6 -mx-6 -mt-6 mb-6 border-b border-gray-200 dark:border-gray-700">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
          <div class="flex items-center w-full md:w-auto">
            <div class="flex items-center relative">
              <Avatar
                  v-model:avatar-path="project.avatar"
                  :placeholder-icon="'pi pi-folder'"
                  :is-editing="isEditing"
                  :alt="project.name"
              />
            </div>
            <div class="ml-4 w-full md:w-auto">
              <div v-if="isEditing" class="flex flex-col gap-2 w-full">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ t("NAME") }}</label>
                <InputText
                    id="editNameInput" v-model="project.name" :placeholder="t('Awesome Idea')"
                    class="w-full dark:bg-gray-700 dark:text-white"
                    :invalid="isNameInvalid" @blur="validateName" @update:model-value="revalidateName"
                />
                <Tag v-if="isNameInvalid" severity="danger" class="w-full" :value="nameError"/>
                <small>{{ t("Name is required") }}</small>
              </div>
              <h3 v-else class="font-bold">{{ project.name }}</h3>
            </div>
          </div>
          <div class="flex flex-col md:flex-row gap-4 items-center justify-center w-full md:w-auto">
            <div v-if="isEditing" class="flex flex-col gap-2 items-start justify-center h-full w-full">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ t("CLIENT") }}</label>
              <IconField class="w-full">
                <InputIcon class="pi pi-user"/>
                <Select
                    v-model="project.clientId" :options="clientsStore.clients" option-label="name" option-value="id"
                    class="w-full dark:bg-gray-700 dark:text-white" :invalid="isClientInvalid" @blur="validateClient"
                    @update:model-value="revalidateClient"
                />
              </IconField>
              <Tag v-if="isClientInvalid" severity="danger" class="w-full" :value="clientError"/>
              <small>{{ t("Client must be selected") }}</small>
            </div>
            <div
                v-else
                class="bg-indigo-50 dark:bg-indigo-900 hover:bg-indigo-100 dark:hover:bg-indigo-800 text-indigo-700 dark:text-indigo-200 px-4 py-2 rounded-full flex items-center transition-colors duration-300 w-full md:w-auto"
            >
              <i class="pi pi-user mr-2"></i>
              <span class="text-sm">{{ selectedClient?.name }}</span>
            </div>
            <div v-if="isEditing" class="flex flex-col gap-2 items-start justify-center h-full w-full">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ t("DUE DATE") }}</label>
              <DatePicker
                  v-model="project.dueDate"
                  class="w-full dark:bg-gray-700 dark:text-white"
                  :show-button-bar="true"
                  input-class="w-full dark:bg-gray-700 dark:text-white"
              />
              <small>{{ t("Select a due date") }}</small>
            </div>
            <div
                v-else-if="project.dueDate && project.dueDate !== ''"
                class="bg-indigo-50 dark:bg-indigo-900 hover:bg-indigo-100 dark:hover:bg-indigo-800 text-indigo-700 dark:text-indigo-200 px-4 py-2 rounded-full flex items-center transition-colors duration-300 w-full md:w-auto"
            >
              <i class="pi pi-calendar mr-2"></i>
              <span class="text-sm">{{ project.dueDate }}</span>
            </div>
          </div>
        </div>
      </div>
    </template>
    <template #content>
      <div class="space-y-6">
        <div v-if="isEditing" class="flex flex-col gap-2 items-start justify-center h-full w-full mb-4">
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ t("DESCRIPTION") }}</label>
          <Textarea v-model="project.description" :placeholder="t('We want to enable customers to be awesome')"
                    class="w-full dark:bg-gray-700 dark:text-white"/>
          <small>{{ t("Describe the project") }}</small>
        </div>
        <p v-else class="text-gray-700 dark:text-gray-300 mb-6">{{ project.description }}</p>

        <template v-if="!isEditing">
          <TimeOverviewGrid
              :time-worked="project.timeWorked"
              :time-estimated="project.timeEstimated"
              :time-left="project.timeLeft"
          />

          <RevenueOverviewGrid
              :income="project.income"
              :expenses="project.expenses"
              :revenue="project.revenue"
              :invoiced="project.invoiced"
              :paid="project.paid"
              :estimated="project.estimated"
          />

          <TaskOverviewGrid
              :in-progress-tasks="project.inProgressTasks"
              :todo-tasks="project.todoTasks"
              :blocked-tasks="project.blockedTasks"
              :completed-tasks="project.completedTasks"
          />
        </template>

        <div class="flex flex-col md:flex-row justify-end gap-4">
          <template v-if="!isEditing">
            <ActionButton @click="isEditing = true" type="secondary" icon="pi pi-pencil" :label="t('Edit')"/>
            <ActionButton @click="confirmDeletion" type="danger" icon="pi pi-trash" :label="t('Delete')"/>
          </template>
          <template v-else>
            <ActionButton @click="discardProject" type="secondary" icon="pi pi-times" :label="t('Discard')"/>
            <ActionButton :disabled="!isValid" @click="saveProject" type="primary" icon="pi pi-check"
                          :label="t('Save Project')"/>
          </template>
        </div>
      </div>
    </template>
  </Card>
</template>