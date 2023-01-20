<template>
  <div class="mx-auto lg:ml-80">
    <section class="py-8">
      <div class="container px-4 mx-auto"><h1 class="mb-2 text-5xl font-bold font-heading">Edit project</h1>
        <form @submit.prevent="submitForm">
          <ProjectForm :wait-for-current="true" />
          <div class="flex flex-wrap space-x-4">
            <SubmitButton>
              <template v-slot:title>Save</template>
              <template v-slot:icon><device-floppy-icon /></template>
            </SubmitButton>
            <BackButton />
          </div>
        </form>
      </div>
    </section>
  </div>
</template>

<script>
import ProjectForm from "@/components/project/ProjectForm.vue";
import { mapGetters, mapState } from "vuex";
import api from "@/api/api";
import store from "@/store";
import BackButton from "@/components/ui/BackButton.vue";
import SubmitButton from "@/components/ui/SubmitButton.vue";
import DeviceFloppyIcon from "vue-tabler-icons/icons/DeviceFloppyIcon";

export default {
  name: "ProjectEdit",
  components: { DeviceFloppyIcon, SubmitButton, BackButton, ProjectForm },
  data() {
    return {
      buttonTitle: "Save",
    };
  },
  computed: mapState({
    project: (state) => state.project.current
  }),
  async created() {
    const project = await api.getProject(this.$route.params.id);
    this.$store.commit("project/setProject", project);
  },
  methods: {
    async submitForm() {
      await api.updateProject(store.state.project.current);
      this.$store.commit("project/clearProject");
      this.$router.push("/projects");
    }
  }
};
</script>

<style scoped>

</style>
