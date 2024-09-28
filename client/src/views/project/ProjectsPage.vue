<script setup lang="ts">
import { useI18n } from 'vue-i18n';
import {computed, ref, onMounted, nextTick} from 'vue';
import { useProjectsStore } from '@/stores/projects';
import ProjectCard from '@/components/project/ProjectCard.vue';
import AddItemFloatingButton from "@/components/navigation/AddItemFloatingButton.vue";
import PageHeader from "@/components/page/PageHeader.vue";

const { t } = useI18n();
const projectsStore = useProjectsStore();
const projects = computed(() => projectsStore.projects);

const showDraftProject = ref(false);

const addDraftProject = async () => {
  showDraftProject.value = true;
  await nextTick();
  const nameInput = document.getElementById('editNameInput');
  if (nameInput) nameInput.scrollIntoView();
};

const removeDraftProject = () => {
  showDraftProject.value = false;
};

onMounted(() => {
  projectsStore.load();
});
</script>

<template>
  <PageHeader title="Projects" icon="pi-folder" />
  <transition name="slide">
    <div v-if="projects.length > 0 || showDraftProject" class="flex container flex-wrap gap-8 mb-8">
      <transition-group name="slide">
        <ProjectCard
          v-for="project in projects"
          :key="project.id"
          :project="project"
          :is-draft="false"
        />
        <ProjectCard
          v-if="showDraftProject"
          key="draft"
          :project="{ id: '', name: '', description: '', client: '', todoTasks: 0, inProgressTasks: 0, blockedTasks: 0, completedTasks: 0, dueDate: '', }"
          :is-draft="true"
          @save="removeDraftProject"
          @discard="removeDraftProject"
        />
      </transition-group>
    </div>
  </transition>
  <AddItemFloatingButton v-if="!showDraftProject" @click="addDraftProject" />
</template>
