<script setup lang="ts">
import {ref, computed, nextTick, onMounted, watch} from 'vue';
import {useI18n} from 'vue-i18n';
import {useProjectsStore} from '@/stores/projects';
import {useClientsStore} from '@/stores/clients';
import InputText from 'primevue/inputtext';
import DatePicker from 'primevue/datepicker';
import Select from 'primevue/select';
import DestructiveActionButton from "@/components/form/DestructiveActionButton.vue";
import ProgressBar from 'primevue/progressbar';
import Avatar from '@/components/form/Avatar.vue';
import {defineProps, defineEmits} from 'vue';
import type Project from '@/interfaces/project';
import PrimaryActionButton from "@/components/form/PrimaryActionButton.vue";
import SecondaryActionButton from "@/components/form/SecondaryActionButton.vue";
import Tag from 'primevue/tag';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import Textarea from 'primevue/textarea';
import type ProjectForm from "@/interfaces/projectForm";
import TaskOverviewGrid from "@/components/report/TaskOverviewGrid.vue";
import TimeOverviewGrid from "@/components/report/TimeOverviewGrid.vue";
import RevenueOverviewGrid from "@/components/report/RevenueOverviewGrid.vue";

const props = defineProps<{
  project: Project;
  isDraft: boolean;
}>();

const {t} = useI18n();
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
    const projectPayload: ProjectForm = {
      ...project.value,
      clientId: project.value.clientId,
    };

    if (props.isDraft) {
      await projectsStore.create(projectPayload);
    } else {
      await projectsStore.update(project.value.id, projectPayload);
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

const updateAvatar = (avatar: string) => {
  project.value.avatar = avatar;
};

const clearAvatar = () => {
  project.value.avatar = '';
};

const hasAvatar = computed(() => project.value.avatar && project.value.avatar !== '');

watch(() => props.project, (newProject) => {
  project.value = {...newProject};
  if (project.value.clientId) {
    const client = clientsStore.clients.find(client => client.id === project.value.clientId);
    if (client) {
      project.value.clientId = client.id;
    }
  }
});

const totalTasks = computed(() => project.value.todoTasks + project.value.inProgressTasks + project.value.blockedTasks + project.value.completedTasks);
const progress = computed(() => totalTasks.value > 0 ? (project.value.completedTasks / totalTasks.value) * 100 : 0);

const selectedClient = computed(() => {
  return clientsStore.clients.find(client => client.id === project.value.clientId);
});
</script>

<template>
  <div
      class="w-full bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden transition-all duration-300 hover:shadow">
    <div class="bg-gradient-to-r from-indigo-400 to-indigo-600 dark:from-indigo-600 dark:to-indigo-800 p-6">
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
          <div class="ml-4 text-white w-full md:w-auto">
            <div v-if="isEditing" class="flex flex-col gap-2 w-full">
              <label class="block text-sm font-medium text-white mb-1">{{ t("NAME") }}</label>
              <InputText
                  id="editNameInput" v-model="project.name" :placeholder="t('Awesome Idea')"
                  class="w-full dark:bg-gray-700 dark:text-white"
                  :invalid="isNameInvalid" @blur="validateName" @update:model-value="revalidateName"/>
              <Tag v-if="isNameInvalid" severity="danger" class="w-full" :value="nameError"/>
              <small class="text-white">{{ t("Name is required") }}</small>
            </div>
            <h3 v-else class="font-bold text-2xl">{{ project.name }}</h3>
          </div>
        </div>
        <div class="flex flex-col md:flex-row gap-4 items-center justify-center h-full w-full md:w-auto">
          <div v-if="isEditing" class="flex flex-col gap-2 items-start justify-center h-full w-full">
            <label class="block text-sm font-medium text-white mb-1">{{ t("CLIENT") }}</label>
            <IconField class="w-full">
              <InputIcon class="pi pi-user"/>
              <Select
                  v-model="project.clientId" :options="clientsStore.clients" option-label="name" option-value="id"
                  class="w-full dark:bg-gray-700 dark:text-white" :invalid="isClientInvalid" @blur="validateClient"
                  @update:model-value="revalidateClient"/>
            </IconField>
            <Tag v-if="isClientInvalid" severity="danger" class="w-full" :value="clientError"/>
            <small class="text-white">{{ t("Client must be selected") }}</small>
          </div>
          <div
              v-else
              class="bg-white dark:bg-gray-700 bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-full flex items-center transition-colors duration-300 w-full md:w-auto">
            <i class="pi pi-user mr-2"></i>
            <span class="text-sm">{{ selectedClient?.name }}</span>
          </div>
          <div v-if="isEditing" class="flex flex-col gap-2 items-start justify-center h-full w-full">
            <label class="block text-sm font-medium text-white mb-1">{{ t("DUE DATE") }}</label>
            <DatePicker
                v-model="project.dueDate"
                class="w-full dark:bg-gray-700 dark:text-white"
                :show-button-bar="true"
                input-class="w-full dark:bg-gray-700 dark:text-white"
            />
            <small class="text-white">{{ t("Select a due date") }}</small>
          </div>
          <div
              v-else-if="project.dueDate && project.dueDate !== ''"
              class="bg-white dark:bg-gray-700 bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-full flex items-center transition-colors duration-300 w-full md:w-auto">
            <i class="pi pi-calendar mr-2"></i>
            <span class="text-sm">{{ project.dueDate }}</span>
          </div>
        </div>
      </div>
    </div>

    <div class="p-4 md:p-6">
      <div v-if="isEditing" class="flex flex-col gap-2 items-start justify-center h-full w-full mb-4">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ t("DESCRIPTION") }}</label>
        <Textarea v-model="project.description" :placeholder="t('We want to enable customers to be awesome')"
                  class="w-full dark:bg-gray-700 dark:text-white"/>
        <small class="text-gray-700 dark:text-gray-300">{{ t("Describe the project") }}</small>
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
        <SecondaryActionButton v-if="!isEditing" @click="isEditing = true">
          <i class="pi pi-pencil mr-2"></i>
          {{ t("Edit") }}
        </SecondaryActionButton>
        <DestructiveActionButton v-if="!isEditing" @click="confirmDeletion">
          <i class="pi pi-trash mr-2"></i>
          {{ t("Delete") }}
        </DestructiveActionButton>
        <PrimaryActionButton v-else :disabled="!isValid" @click="saveProject">
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
</template>