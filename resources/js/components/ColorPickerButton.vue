<template>
    <div ref="picker"
         class="inline-block relative"
         v-on:mouseover="showing = true"
         v-on:mouseout="showing = false"
    >
        <i class="fas fa-palette"></i>
        <div ref="popover"
             class="bg-white pl-1 py-1 shadow-lg rounded"
             :class="{ 'flex': showing, 'hidden': !showing }"
        >
            <div v-for="color in colors"
                 class="w-8 h-8 shadow cursor-pointer rounded-full mr-1 border flex items-center justify-center"
                 :class="`bg-${color}-lighter border-${color}-light`"
                 @click="toggleColor(color)"
            >
                <i v-show="isCurrentColor(color)" class="fas fa-check" :class="`text-${color}-dark`"></i>
            </div>
        </div>
    </div>
</template>

<script>
import Popper from 'popper.js';

export default {
  props: ['color'],

  data: () => ({
    showing: false,
    selectedColor: null,
    colors: ['white', 'red', 'orange', 'yellow', 'green', 'teal', 'blue', 'indigo', 'purple', 'pink']
  }),

  mounted() {
    this.setupPopper();
  },

  methods: {
    setupPopper() {
      new Popper(this.$refs.picker, this.$refs.popover, {
        placement: 'auto'
      });
    },

    isCurrentColor(color) {
      return this.color === color;
    },

    toggleColor(color) {
      this.$emit('color-toggled', color)
    }
  }
};
</script>