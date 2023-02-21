<template>
  <section class="py-5 px-6 bg-white shadow">
    <nav class="relative">
      <div class="flex items-center">
        <transition name="slide-up-down" @enter="enter" @leave="leave">
          <div v-if="!isNavBarCollapsed" class="flex flex-wrap gap-3 content-start items-center">
            <toggle-timer-button :size="12" :global="true" />
            <pomodoro-timer />
            <timer-time />
            <start-time />
            <end-time />
            <tracked-subject />
            <transition name="fade" mode="in-out">
              <moon-loader
                v-if="!isSynchronised"
                title="Synchronising..."
                :size="spinnerSize"
                :color="spinnerColor"
                :loading="!isSynchronised"
              />
            </transition>
          </div>
          <div v-else class="flex flex-row gap-3 content-start items-center">
            <toggle-timer-button :size="8" :global="true" />
            <pomodoro-timer :size="8" />
            <transition name="fade" mode="in-out">
              <moon-loader
                v-if="!isSynchronised"
                title="Synchronising..."
                :size="spinnerSize"
                :color="spinnerColor"
                :loading="!isSynchronised"
              />
            </transition>
          </div>
        </transition>
        <div :class="isNavBarCollapsed ? '-mb-9' : '-mb-10'" class="lg:hidden absolute bottom-0 right-0">
          <collapse-nav-bar-button />
        </div>
      </div>
    </nav>
  </section>
</template>

<script>
import ToggleTimerButton from "@/components/ui/ToggleTimerButton.vue";
import TrackedSubject from "@/components/ui/TrackedSubject.vue";
import TimerTime from "@/components/ui/TimerTime.vue";
import { mapGetters } from "vuex";
import { MoonLoader } from "vue3-spinner";
import StartTime from "@/components/ui/StartTime.vue";
import EndTime from "@/components/ui/EndTime.vue";
import PomodoroTimer from "@/components/ui/PomodoroTimer.vue";
import CollapseNavBarButton from "@/components/ui/CollapseNavBarButton.vue";

export default {
  name: "HeaderNavbar",
  data() {
    return {
      spinnerColor: "#382CDD",
      spinnerSize: "22px",
    };
  },
  computed: {
    ...mapGetters(["isSynchronised", "isNavBarCollapsed"])
  },
  methods: {
    enter(el) {
      el.style.opacity = 0;
      el.style.transform = 'translateY(-100%)';
      setTimeout(() => {
        el.style.opacity = 1;
        el.style.transform = '';
      }, 300);
    },
    leave(el, done) {
      el.style.opacity = 1;
      el.style.transform = '';
      setTimeout(() => {
        el.style.opacity = 0;
        el.style.transform = 'translateY(-100%)';
        setTimeout(() => {
          done();
        }, 0);
      }, 300);
    }
  },
  components: {
    CollapseNavBarButton,
    MoonLoader,
    PomodoroTimer,
    EndTime,
    StartTime,
    TimerTime,
    TrackedSubject,
    ToggleTimerButton
  }
};
</script>

<style>
.v-select .vs__selected-options {
  flex-wrap: nowrap;
  overflow: hidden;
  width: 40px;
  max-height: 28px;
}

.slide-up-down-enter-active, .slide-up-down-leave-active {
  transition: transform 0.8s cubic-bezier(.66,.5,.52,1.29);
}

.slide-up-down-enter {
  transform: translateY(-100%);
  opacity: 0;
}

.slide-up-down-leave-to {
  transform: translateY(-100%);
  opacity: 0;
}

</style>
