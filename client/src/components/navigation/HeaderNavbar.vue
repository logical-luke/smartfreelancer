<template>
  <nav
    class="sticky top-0 py-3 px-4 bg-white shadow"
    :class="{ 'transition-all duration-700 ease-linear': true }"
    :style="{ height: navHeight }"
  >
    <transition name="slide-up-down" @enter="enter" @leave="leave">
      <div
        v-if="!isNavBarCollapsed"
        class="flex flex-wrap gap-3 content-start items-center"
      >
        <div class="flex items-center gap-3">
          <timer-mode-toggle />
          <timer-button :size="12" :global="true" />
          <pomodoro-timer :size="12" />
          <duration-input />
        </div>
        <div class="flex items-center w-auto gap-3">
          <select-subject-button />
          <description-input />
        </div>
      </div>
      <div v-else class="flex gap-3 items-center">
        <timer-mode-toggle :size="8" />
        <timer-button :size="8" :global="true" />
        <pomodoro-timer :size="8" />
        <select-subject-button :size="8" />
        <duration-input />
      </div>
    </transition>
    <div
      :class="isNavBarCollapsed ? '-mb-3' : '-mb-4'"
      class="md:hidden absolute bottom-0 right-0 mr-3"
    >
      <collapse-nav-bar-button
        @click="slideFinished = !slideFinished"
        :slide-finished="slideFinished"
      />
    </div>
  </nav>
</template>

<style>
.v-select .vs__selected-options {
  flex-wrap: nowrap;
  overflow: hidden;
  width: 40px;
  max-height: 28px;
}

.slide-up-down-enter-active,
.slide-up-down-leave-active {
  transition: transform 0.8s cubic-bezier(0.66, 0.5, 0.52, 1.29);
}

.slide-up-down-enter {
  transform: translateY(-100%);
  opacity: 0;
}

.slide-up-down-leave-to {
  transform: translateY(-100%);
  opacity: 0;
}

nav {
  transition: height 0.2s cubic-bezier(0.66, 0.5, 0.52, 1.29);
}
</style>

<script>
import TimerButton from "@/components/timer/TimerButton.vue";
import SelectSubjectButton from "@/components/timer/SelectSubjectButton.vue";
import DurationInput from "@/components/DurationInput.vue";
import { mapGetters } from "vuex";
import PomodoroTimer from "@/components/PomodoroTimer.vue";
import CollapseNavBarButton from "@/components/CollapseNavBarButton.vue";
import DescriptionInput from "../DescriptionInput.vue";
import TimerModeToggle from "@/components/timer/TimerModeToggle.vue";

export default {
  name: "HeaderNavbar",
  data() {
    return {
      slideFinished: true,
      navHeight: null,
    };
  },
  computed: {
    ...mapGetters("settings", ["isNavBarCollapsed"]),
  },
  methods: {
    enter(el) {
      const navHeight = el.offsetHeight + 20;
      this.navHeight = `${navHeight}px`;
      el.style.opacity = 0;
      el.style.transform = "translateY(-100%)";
      setTimeout(() => {
        el.style.opacity = 1;
        el.style.transform = "";
        setTimeout(() => {
          this.slideFinished = true;
        }, 400);
      }, 400);
    },
    leave(el, done) {
      const navHeight = el.offsetHeight + 20;
      this.navHeight = `${navHeight}px`;
      el.style.opacity = 1;
      el.style.transform = "";
      setTimeout(() => {
        el.style.opacity = 0;
        el.style.transform = "translateY(-100%)";
        setTimeout(() => {
          done();
        }, 100);
      }, 300);
    },
  },
  components: {
    TimerModeToggle,
    DescriptionInput,
    CollapseNavBarButton,
    PomodoroTimer,
    DurationInput,
    SelectSubjectButton,
    TimerButton,
  },
};
</script>
