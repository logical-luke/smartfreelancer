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
          <toggle-timer-button :size="12" :global="true" />
          <pomodoro-timer :size="12" />
          <timer-time />
        </div>
        <div class="flex items-center gap-3">
          <start-time />
          <end-time />
        </div>
        <div class="flex items-center w-1/2 gap-3">
            <select-subject-button />
            <description-input />
        </div>
      </div>
      <div v-else class="flex gap-3 items-center">
        <toggle-timer-button :size="8" :global="true" />
        <pomodoro-timer :size="8" />
        <select-subject-button :size="8" />
      </div>
    </transition>
    <div
      :class="isNavBarCollapsed ? 'mb-3' : '-mb-6'"
      class="absolute bottom-0 right-0 mr-3"
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
import ToggleTimerButton from "@/components/ui/ToggleTimerButton.vue";
import SelectSubjectButton from "@/components/ui/SelectSubjectButton.vue";
import TimerTime from "@/components/ui/TimerTime.vue";
import { mapGetters } from "vuex";
import StartTime from "@/components/ui/StartTime.vue";
import EndTime from "@/components/ui/EndTime.vue";
import PomodoroTimer from "@/components/ui/PomodoroTimer.vue";
import CollapseNavBarButton from "@/components/ui/CollapseNavBarButton.vue";
import DescriptionInput from "./DescriptionInput.vue";

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
    DescriptionInput,
    CollapseNavBarButton,
    PomodoroTimer,
    EndTime,
    StartTime,
    TimerTime,
    SelectSubjectButton,
    ToggleTimerButton,
  },
};
</script>
