<template>
  <div class="mx-auto lg:ml-80">
    <section class="py-8">
      <div class="container px-4 mx-auto">
        <h1 class="mb-2 text-5xl font-bold font-heading">Add project</h1>
        <form @submit.prevent="submitForm">
          <ProjectForm />
          <div class="flex flex-wrap space-x-4">
            <div>
              <SubmitButton>
                <template v-slot:title>Add</template>
                <template v-slot:icon>
                  <square-plus-icon />
                </template>
              </SubmitButton>
            </div>
            <div>
              <BackButton />
            </div>
          </div>
        </form>
      </div>
    </section>
  </div>
</template>

<script>
import ProjectForm from "@/components/project/ProjectForm.vue";
import { mapState } from "vuex";
import BackButton from "@/components/ui/BackButton.vue";
import SubmitButton from "@/components/ui/SubmitButton.vue";
import SquarePlusIcon from "vue-tabler-icons/icons/SquarePlusIcon";

export default {
  name: "ProjectCreate",
  components: { SquarePlusIcon, SubmitButton, BackButton, ProjectForm },
  computed: mapState({
    project: (state) => state.project.current,
    userId: (state) => state.user.id,
  }),
  created() {
    this.$store.dispatch("project/clearProject");
  },
  methods: {
    async submitForm() {
      await this.$store.dispatch("projects/createProject", this.project);

      this.$router.push("/projects");
    },
  },
};
</script>

<style scoped></style>
