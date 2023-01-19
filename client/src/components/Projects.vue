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
            <router-link :to="`/project/edit/${project.id}`">
              <a class="flex mr-4 items-center text-xs text-gray-500">
                <button
                  class="inline-block w-full md:w-auto px-6 py-3 font-medium text-white bg-indigo-500 hover:bg-indigo-600 rounded transition duration-200">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                       fill="currentColor"
                       class="h-4 w-4 text-white">

                    <path
                      d="m14.06 9.02.92.92L5.92 19H5v-.92l9.06-9.06M17.66 3c-.25 0-.51.1-.7.29l-1.83 1.83 3.75 3.75 1.83-1.83a.996.996 0 0 0 0-1.41l-2.34-2.34c-.2-.2-.45-.29-.71-.29zm-3.6 3.19L3 17.25V21h3.75L17.81 9.94l-3.75-3.75z"></path>
                  </svg>
                </button>
              </a>
            </router-link>
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
    projects: (state) => state.projects.all
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
