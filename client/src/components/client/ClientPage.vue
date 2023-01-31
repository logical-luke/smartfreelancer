<template>
  <div class="w-full transition-element md:w-1/2 px-4 mb-4 md:mb-0">
    <section class="py-8">
      <div class="container px-4 mx-auto">
        <div class="p-6 bg-white rounded shadow">
          <div class="mb-4">
            <h3 class="mb-2 font-medium">{{ name }}</h3>
            <p class="text-sm text-gray-500">
              {{ description }}
            </p>
          </div>
          <div class="flex justify-between">
            <div class="flex items-center">
              <ToggleTimerButton :client-id="id" />
            </div>
            <div class="flex text-white">
              <router-link :to="`/client/edit/${id}`">
                <a class="flex mr-4 items-center text-sm">
                  <EditButton />
                </a>
              </router-link>
              <a class="flex mr-4 items-center text-sm">
                <DeleteButton @confirm="deleteClient(id)" :subject="name" />
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import DeleteButton from "@/components/ui/DeleteButton.vue";
import EditButton from "@/components/ui/EditButton.vue";
import { mapActions } from "vuex";
import ToggleTimerButton from "@/components/ui/ToggleTimerButton.vue";

export default {
  name: "ClientPage",
  components: { ToggleTimerButton, EditButton, DeleteButton },
  props: {
    name: {
      type: String,
      required: true,
    },
    description: {
      type: String,
    },
    id: {
      type: String,
      required: true,
    },
  },
  methods: {
    ...mapActions("clients", ["deleteClient"]),
  },
};
</script>

<style scoped></style>
