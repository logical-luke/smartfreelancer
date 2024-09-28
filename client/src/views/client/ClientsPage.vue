<script setup lang="ts">
import { useI18n } from 'vue-i18n';
const { t } = useI18n();
import {computed, ref, onMounted, nextTick} from 'vue';
import { useClientsStore } from '@/stores/clients';
import ClientCard from '@/components/client/ClientCard.vue';
import AddItemFloatingButton from "@/components/navigation/AddItemFloatingButton.vue";
import PageHeader from "@/components/page/PageHeader.vue";

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
  <PageHeader title="Clients" icon="pi-user" />
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
  <AddItemFloatingButton v-if="!showDraftClient" @click="addDraftClient" />
</template>
