<template>
  <Sidebar />
  <div class="mx-auto lg:ml-80">
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
            <button class="mr-2">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                   class="h-4 w-4 text-gray-200"><path
                d="M14.12 10.47 12 12.59l-2.13-2.12-1.41 1.41L10.59 14l-2.12 2.12 1.41 1.41L12 15.41l2.12 2.12 1.41-1.41L13.41 14l2.12-2.12zM15.5 4l-1-1h-5l-1 1H5v2h14V4zM6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM8 9h8v10H8V9z"></path></svg>
            </button>
              </a>
              <a class="flex items-center text-xs text-gray-500" href="#">
                <span class="mr-2">
                  <svg class="h-4 w-4 text-gray-200" viewBox="0 0 15 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                      d="M13.0837 9.33334L7.91699 14.5C6.50032 15.9167 4.25033 15.9167 2.91699 14.5C1.50033 13.0833 1.50033 10.8333 2.91699 9.50001L9.58366 2.83334C10.417 2.08334 11.667 2.08334 12.5003 2.83334C13.3337 3.66668 13.3337 5.00001 12.5003 5.75001L6.75033 11.5C6.50033 11.75 6.08366 11.75 5.83366 11.5C5.58366 11.25 5.58366 10.8333 5.83366 10.5833L10.0837 6.33334C10.417 6.00001 10.417 5.50001 10.0837 5.16668C9.75032 4.83334 9.25032 4.83334 8.91699 5.16668L4.66699 9.50001C3.75033 10.4167 3.75033 11.8333 4.66699 12.75C5.58366 13.5833 7.00032 13.5833 7.91699 12.75L13.667 7.00001C15.167 5.50001 15.167 3.16668 13.667 1.66668C12.167 0.166676 9.83366 0.166676 8.33366 1.66668L1.66699 8.33334C0.666992 9.33334 0.166992 10.6667 0.166992 12C0.166992 14.9167 2.50032 17.1667 5.41699 17.1667C6.83366 17.1667 8.08366 16.5833 9.08366 15.6667L14.2503 10.5C14.5837 10.1667 14.5837 9.66668 14.2503 9.33334C13.917 9.00001 13.417 9.00001 13.0837 9.33334Z"
                      fill="currentColor"></path>
                  </svg>
                </span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import { mapActions, mapGetters, mapState } from "vuex";
import Sidebar from "@/components/Sidebar.vue";

let projects = {};

export default {
  name: "Projects",
  components: { Sidebar },
  computed: mapState({
    projects: (state) => state.projects.all,
    ...mapGetters(["getToken"])
  }),
  methods: {
    ...mapActions('projects',
      ['deleteProject']
    )
  },
  created() {
    this.$store.dispatch("projects/getAllProjects");
  }
};
</script>

<style scoped></style>
