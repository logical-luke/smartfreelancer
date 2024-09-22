<script setup lang="ts">
import { useI18n } from 'vue-i18n';
const { t } = useI18n();
import { computed, ref, onMounted, nextTick } from 'vue';
import { useClientsStore } from '@/stores/clients';
import ClientItem from '@/components/client/ClientItem.vue';
import DraftClientItem from '@/components/client/DraftClientItem.vue';

const clientsStore = useClientsStore();
const clients = computed(() => clientsStore.clients);

const showDraftClient = ref(false);

const addDraftClient = () => {
  showDraftClient.value = true;
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
        <ClientItem
            v-for="client in clients"
            :key="client.id"
            :id="client.id"
            :name="client.name"
            :email="client.email"
            :phone="client.phone"
            :avatar="client.avatar"
            :revenue="client.revenue"
            :time-worked="client.timeWorked"
            :todo-tasks="client.todoTasks"
            :in-progress-tasks="client.inProgressTasks"
            :blocked-tasks="client.blockedTasks"
            :completed-tasks="client.completedTasks"
            :internal="client.internal"
        />
        <DraftClientItem
            v-if="showDraftClient"
            key="draft"
            @save="removeDraftClient"
            @discard="removeDraftClient"
        />
      </transition-group>
    </div>
  </transition>
  <button
      @click="addDraftClient"
      class="fixed bottom-6 right-6 w-16 h-16 bg-indigo-500 text-white rounded-full shadow-lg hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-300 flex items-center justify-center"
  >
    <i class="pi pi-plus text-2xl"></i>
  </button>
</template>
