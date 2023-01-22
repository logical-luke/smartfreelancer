<template>
  <transition name="fade">
    <p v-if="subject">{{ subject }}</p>
  </transition>
</template>

<script>
import { mapState } from "vuex";

export default {
  name: "TrackedProject",
  computed: mapState({
    timer: (state) => state.timer.current,
    subject: (state) => state.timer.current.subjectName
  })
};
</script>

<style scoped>

</style>
