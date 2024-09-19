<script lang="ts">
import { defineComponent, computed } from 'vue';
import { useRouter } from 'vue-router';
import { useClientsStore } from '@/stores/clients';
import ClientItem from '@/components/client/ClientItem.vue';
import MainActionButton from '@/components/MainActionButton.vue';

export default defineComponent({
  name: 'ClientsPage',
  components: {
    MainActionButton,
    ClientItem,
  },
  setup() {
    const clientsStore = useClientsStore();
    const router = useRouter();
    const addClientRoute = { name: 'AddClientPage' };

    const clients = computed(() => clientsStore.clients);

    const goToAddClient = async () => {
      await router.push(addClientRoute);
    };

    clientsStore.load();

    return {
      clients,
      goToAddClient,
    };
  },
});
</script>

<template>
  <div class="flex mb-8">
    <div>
      <h3 class="text-xl font-bold">{{ $t("Clients") }}</h3>
    </div>
  </div>
  <transition name="slide">
    <div v-if="clients.length > 0" class="flex container flex-wrap gap-8 mb-8">
      <transition-group name="slide">
        <ClientItem
          v-for="client in clients"
          :id="client.id"
          :key="client.id"
          :name="client.name"
          :email="client.email"
          :phone="client.phone"
          :avatar="client.avatar"
          :revenue="client.revenue"
          :time-worked="client.timeWorked"
          :ongoing-tasks="client.ongoingTasks"
          :planned-tasks="client.plannedTasks"
          :finished-tasks="client.finishedTasks"
          :blocked-tasks="client.blockedTasks"
        />
      </transition-group>
    </div>
  </transition>
  <MainActionButton class="w-full md:w-auto" @click="goToAddClient">{{
    $t("Add Client")
  }}</MainActionButton>
</template>
