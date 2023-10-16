<template>
  <input-text @update:model-value="setDescription" v-model="description" :placeholder="$t('Description')" />
</template>

<style scoped>

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
