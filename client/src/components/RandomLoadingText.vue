<template>
  {{ this.text }}
</template>

<script>
import { mapState } from "vuex";

export default {
  name: "RandomLoadingText",
  data() {
    return {
      texts: [],
      text: "",
    };
  },
  watch: {
    locale() {
      this.updateTexts();
      this.setText();
    },
  },
  computed: {
    ...mapState({
      locale: (state) => state.settings.locale,
    }),
  },
  methods: {
    setText() {
      this.text = this.texts[Math.floor(Math.random() * this.texts.length)];
    },
    updateTexts() {
      this.texts = [
        this.$t(
          "Flexing our digital muscles for your task management",
          this.locale
        ),
        this.$t(
          "Training bytes to be marathon runners of productivity",
          this.locale
        ),
        this.$t("Pushing the envelope of your freelance panorama", this.locale),
        this.$t(
          "Baking a pie full of features for your successful journey",
          this.locale
        ),
        this.$t(
          "Bytes are doing lunges to match your productivity pace",
          this.locale
        ),
        this.$t(
          "Adding caffeine shots to the app for relentless task handling",
          this.locale
        ),
        this.$t("Riding the workflow high wave just for you", this.locale),
        this.$t(
          "Planting the seeds of organization in your project garden",
          this.locale
        ),
        this.$t(
          "Running an app triathlon for your seamless usability",
          this.locale
        ),
        this.$t(
          "Dodging freelance bullets like a pro for smooth sailing",
          this.locale
        ),
        this.$t(
          "Time tracking is stretching its seconds for your convenience",
          this.locale
        ),
        this.$t(
          "Designing a productivity wardrobe for your project fashion",
          this.locale
        ),
        this.$t(
          "Project interface is doing makeup for a flawless look",
          this.locale
        ),
        this.$t(
          "Adding energizing vitamins to your freelancing diet",
          this.locale
        ),
        this.$t(
          "Giving bytes a protein shake for increased power",
          this.locale
        ),
        this.$t(
          "Creating a fitness regime for your workload management",
          this.locale
        ),
        this.$t(
          "Baking a fresh pie of projects for your freelance bakery",
          this.locale
        ),
        this.$t(
          "Preparing a power breakfast for your task appetite",
          this.locale
        ),
        this.$t(
          "Getting agile like a cat for your prompt delivery",
          this.locale
        ),
        this.$t(
          "Videos are doing Zumba for your entertainment and learning",
          this.locale
        ),
        this.$t(
          "Sculpting a minimalist user interface like a modern art",
          this.locale
        ),
        this.$t(
          "Doing the wave with tasks for your organization cheerleading",
          this.locale
        ),
        this.$t(
          "On a treasure hunt to find the gem that is your next task",
          this.locale
        ),
        this.$t(
          "Tasks are having a spa day to be rejuvenated for you",
          this.locale
        ),
        this.$t(
          "Rolling the dice of probability for your task scheduling",
          this.locale
        ),
        this.$t("Doing a raindance for a shower of productivity", this.locale),
        this.$t(
          "Assigning tasks on the tic-tac-toe board of your day",
          this.locale
        ),
        this.$t(
          "Track time is on treadmill to be in shape for you",
          this.locale
        ),
        this.$t("Preparing a feast of features for your busy day", this.locale),
        this.$t(
          "Tasks are sniffing out truffles in your forest of work",
          this.locale
        ),
        this.$t(
          "Doing a haka to prepare for the game of freelancing",
          this.locale
        ),
        this.$t(
          "Blowing dandelions to spread the spores of success",
          this.locale
        ),
        this.$t(
          "Running code sprints for a faster journey to your goals",
          this.locale
        ),
        this.$t(
          "Polishing the hourglass for a shiny time tracking",
          this.locale
        ),
        this.$t(
          "On the runway to launch your freelancing into orbit",
          this.locale
        ),
        this.$t(
          "Doing a digital detox to keep the interface clutter-free",
          this.locale
        ),
        this.$t(
          "Whipping up some butter for a smooth freelancer journey",
          this.locale
        ),
        this.$t(
          "Automating a dance sequence to match your workflow rhythm",
          this.locale
        ),
        this.$t(
          "The app is on a productivity treadmill, no getting off now",
          this.locale
        ),
        this.$t(
          "Playing the harmony of performance on the sitar of bytes",
          this.locale
        ),
        this.$t("Organizing a flashmob for your task list", this.locale),
        this.$t(
          "Waxing on and off for a smooth sailing in your freelancing journey",
          this.locale
        ),
        this.$t(
          "Completion badges are training for an award-winning performance",
          this.locale
        ),
        this.$t(
          "Carving out a pint of project management for a cool day",
          this.locale
        ),
        this.$t(
          "Training spiders to weave the web of your network",
          this.locale
        ),
        this.$t(
          "Sharpening the axe to cut through the task forest",
          this.locale
        ),
        this.$t("Applying sunscreen to shield against burnout", this.locale),
        this.$t(
          "On a diving expedition to explore your potential depths",
          this.locale
        ),
        this.$t(
          "Journey to the center of the app for a productive core",
          this.locale
        ),
        this.$t(
          "Bytes are sipping on Gatorade for an effective sprint",
          this.locale
        ),
        this.$t(
          "Roasting marshmallows for a cozy freelancer campfire",
          this.locale
        ),
        this.$t(
          "The clock is on a swing for a rhythmic time tracking",
          this.locale
        ),
        this.$t("Printing a map for your freelance expedition", this.locale),
        this.$t(
          "Tasks are crowning themselves for a royal performance",
          this.locale
        ),
        this.$t(
          "Doing a headstand for a fresh perspective on freelancing",
          this.locale
        ),
        this.$t("The app is meditating for a Zen-like workflow", this.locale),
        this.$t(
          "Stirring a potion of features to cast a productivity spell",
          this.locale
        ),
        this.$t(
          "Features are doing the Floss Dance for a smooth run",
          this.locale
        ),
        this.$t(
          "Setting the stage for a blockbuster performance of your tasks",
          this.locale
        ),
        this.$t(
          "Making a bouquet from the garden of your project successes",
          this.locale
        ),
      ];
    },
  },
  mounted() {
    this.updateTexts();
    this.setText();
  },
};
</script>
