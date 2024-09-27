<script setup lang="ts">
import { useI18n } from 'vue-i18n';
const { t } = useI18n();
import {computed, ref, onMounted, nextTick} from 'vue';
import { useClientsStore } from '@/stores/clients';
import ClientCard from '@/components/client/ClientCard.vue';

const clientsStore = useClientsStore();
const clients = computed(() => clientsStore.clients);

const showDraftClient = ref(false);

const addDraftClient = async () => {
  showDraftClient.value = true;
  await nextTick();
  const nameInput = document.getElementById('editNameInput');
  if (nameInput) nameInput.scrollIntoView();
};

const removeDraftClient = () => {
  showDraftClient.value = false;
};

onMounted(() => {
  clientsStore.load();
});
</script>

<template>
  <div class="flex mb-8">
    <div>
      <h3 class="text-2xl font-bold">{{ t("Clients") }}</h3>
    </div>
  </div>
  <transition name="slide">
    <div v-if="clients.length > 0 || showDraftClient" class="flex container flex-wrap gap-8 mb-8">
      <transition-group name="slide">
        <ClientCard
            v-for="client in clients"
            :key="client.id"
            :client="client"
            :is-draft="false"
        />
        <ClientCard
            v-if="showDraftClient"
            key="draft"
            :client="{ id: '', ownerId: '', name: '', avatar: null, phone: null, email: null, createdAt: Date.now(), revenue: 0, timeWorked: 0, todoTasks: 0, inProgressTasks: 0, blockedTasks: 0, completedTasks: 0, internal: false }"
            :is-draft="true"
            @save="removeDraftClient"
            @discard="removeDraftClient"
        />
      </transition-group>
    </div>
  </transition>
  <button
      class="fixed bottom-6 right-6 w-16 h-16 bg-indigo-500 text-white rounded-full shadow hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-300 flex items-center justify-center"
      @click="addDraftClient"
  >
    <i class="pi pi-plus text-2xl"></i>
  </button>
</template>
