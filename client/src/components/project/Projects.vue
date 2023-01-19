<template>
  <div class="mx-auto lg:ml-80">
    <NewButton go-to="/project/create/">Project</NewButton>
    <transition name="fade">
      <div>
        <section class="py-8" v-for="project in projects" :key="project.id">
          <div class="container px-4 mx-auto">
            <div class="p-6 bg-white rounded shadow">
              <div class="mb-4">
                <h3 class="mb-2 font-medium">{{ project.name }}</h3>
                <p class="text-sm text-gray-500">
                  {{ project.description }}
                </p>
              </div>
              <div class="flex justify-between">
                <div class="flex items-center">

                </div>
                <div class="flex text-white">
                  <router-link :to="`/project/edit/${project.id}`">
                    <a class="flex mr-4 items-center text-sm">
                      <EditButton />
                    </a>
                  </router-link>
                  <a class="flex mr-4 items-center text-sm">
                    <DeleteButton @onConfirm="deleteProject(project.id)" :subject="project.name" />
                  </a>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
    </transition>
  </div>
</template>

<script>
import { mapActions, mapGetters, mapState } from "vuex";
import DeleteButton from "@/components/ui/DeleteButton.vue";
import EditButton from "@/components/ui/EditButton.vue";
import NewButton from "@/components/ui/NewButton.vue";

let projects = {};

export default {
  name: "Projects",
  components: { NewButton, EditButton, DeleteButton },
  computed: mapState({
    projects: (state) => state.projects.all
  }),
  methods: {
    ...mapActions("projects",
      ["deleteProject"]
    )
  },
  created() {
    this.$store.dispatch("projects/getAllProjects");
  },
  mounted() {
    this.$store.commit("project/clearProject");
  }
};
</script>

<style scoped></style>
