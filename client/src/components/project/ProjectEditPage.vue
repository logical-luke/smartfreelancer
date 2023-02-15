<template>
  <div class="container px-4 mx-auto">
    <h1 class="mb-2 text-xl font-bold font-heading">Edit project</h1>
    <form @submit.prevent="submitForm">
      <ProjectForm />
      <div class="flex flex-wrap space-x-4">
        <div>
          <SubmitButton>
            Save
            <template #icon>
              <device-floppy-icon size="20" />
            </template>
          </SubmitButton>
        </div>
        <div>
          <BackButton />
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import ProjectForm from "@/components/project/ProjectForm.vue";
import { mapState } from "vuex";
import BackButton from "@/components/ui/BackButton.vue";
import SubmitButton from "@/components/ui/SubmitButton.vue";
import DeviceFloppyIcon from "vue-tabler-icons/icons/DeviceFloppyIcon";
import router from "@/router";
import store from "@/store";
import { useRoute } from "vue-router";

export default {
  name: "ProjectEditPage",
  components: { DeviceFloppyIcon, SubmitButton, BackButton, ProjectForm },
  data() {
    return {
      buttonTitle: "Save"
    };
  },
  computed: mapState({
    project: (state) => state.project.current
  }),
  async created() {
    const project = store.getters["projects/getProjectById"](
      this.route.params.id
    );
    store.commit("project/setProject", project);
  },
  methods: {
    async submitForm() {
      await store.dispatch("projects/updateProject", this.project);
      await router.push("/projects");
    }
  },
  setup() {
    const route = useRoute();

    return { route };
  }
};
</script>

<style scoped></style>
