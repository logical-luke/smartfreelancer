<template>
  <div class="max-w-fit ">
    <input-text class="borderless-input font-bold" @update:model-value="setDescription" v-model="description" :placeholder="$t('Describe your efforts')"/>
  </div>
</template>

<style scoped>
.borderless-input {
  border: none;
  box-shadow: none;
}

.p-inputtext {
  font-weight: 700;
}
</style>


<script>

import InputText from 'primevue/inputtext';
import timer from "@/services/timer";
import {mapState} from "vuex";

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
