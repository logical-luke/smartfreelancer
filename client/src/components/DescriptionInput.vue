<template>
  <div class="max-w-fit">
    <InputText
      v-model="description"
      class="borderless-input font-bold"
      :placeholder="$t('Log your magic moment! 🚀')"
      @update:model-value="setDescription"
    />
  </div>
</template>

<script>
import InputText from "primevue/inputtext";
import timer from "@/services/timer";
import { mapState } from "vuex";

export default {
  name: "DescriptionInput",
  data() {
    return {
      description: "",
    };
  },
  methods: {
    setDescription(value) {
      timer.setDescription(value);
    },
  },
  computed: {
    ...mapState({
      timer: (state) => state.timer.current,
    }),
  },
  components: {
    InputText,
  },
  mounted() {
    this.description = this.timer.description;
  },
};
</script>

<style scoped>
.borderless-input {
  border: none;
  box-shadow: none;
}

.p-inputtext {
  font-weight: 700;
  color: black;
}
</style>
