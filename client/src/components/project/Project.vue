<template>
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
            <ToggleTimerButton :project-id="id" />
          </div>
          <div class="flex text-white">
            <router-link :to="`/project/edit/${id}`">
              <a class="flex mr-4 items-center text-sm">
                <EditButton />
              </a>
            </router-link>
            <a class="flex mr-4 items-center text-sm">
              <DeleteButton @onConfirm="deleteProject(id)" :subject="name" />
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import DeleteButton from "@/components/ui/DeleteButton.vue";
import EditButton from "@/components/ui/EditButton.vue";
import { mapActions } from "vuex";
import ToggleTimerButton from "@/components/ui/ToggleTimerButton.vue";

export default {
  name: "Project",
  components: { ToggleTimerButton, EditButton, DeleteButton },
  props: {
    name: {
      type: String,
      required: true
    },
    description: {
      type: String
    },
    id: {
      type: Number,
      required: true
    }
  },
  methods: {
    ...mapActions("projects",
      ["deleteProject"]
    )
  }
};
</script>

<style scoped>

</style>
