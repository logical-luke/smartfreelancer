<template>
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
          <div class="flex">
            <a class="flex mr-4 items-center text-xs text-gray-500" @click="deleteProject(project.id)">
              <button
                class="inline-block w-full md:w-auto px-6 py-3 font-medium text-white bg-red-400 hover:bg-red-600 rounded transition duration-200">
                <svg xmlns="http://www.w3.org/2000/svg"
                     width="24" height="24" viewBox="0 0 24 24"
                     fill="currentColor"
                     class="h-4 w-4 text-white">
                  <path
                    d="M14.12 10.47 12 12.59l-2.13-2.12-1.41 1.41L10.59 14l-2.12 2.12 1.41 1.41L12 15.41l2.12 2.12 1.41-1.41L13.41 14l2.12-2.12zM15.5 4l-1-1h-5l-1 1H5v2h14V4zM6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM8 9h8v10H8V9z"></path>
                </svg>
              </button>
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import { mapActions, mapGetters, mapState } from "vuex";
import Sidebar from "@/components/Sidebar.vue";

let projects = {};

export default {
  name: "Projects",
  computed: mapState({
    projects: (state) => state.projects.all,
  }),
  methods: {
    ...mapActions("projects",
      ["deleteProject"]
    )
  },
  created() {
    this.$store.dispatch("projects/getAllProjects");
  }
};
</script>

<style scoped></style>
