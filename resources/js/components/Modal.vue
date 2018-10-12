<template>
    <portal to="modals" v-if="show">
        <div class="bg-grey-transparent fixed pin-t pin-l h-full w-full flex flex-col items-center justify-center p-2 z-50 overflow-scroll">
            <on-click-outside :do="cancel" class="max-h-full">
                <slot></slot>
            </on-click-outside>
        </div>
    </portal>
</template>

<script>
import OnClickOutside from './OnClickOutside.vue';

export default {
  props: {
    show: { default: false }
  },

  components: { OnClickOutside },

  created() {
    const escapeHandler = (e) => {
      if (e.key === 'Escape' && this.show) {
        this.cancel();
      }
    };

    document.addEventListener('keydown', escapeHandler);

    this.$once('hook:destroyed', () => {
      document.removeEventListener('keydown', escapeHandler)
    })
  },

  methods: {
    cancel() {
      this.$emit('close');
    },
  },

  watch: {
    show: {
      immediate: true,
      handler: (show) => {
        if (show) {
          document.body.style.setProperty('overflow', 'hidden')
        } else {
          document.body.style.removeProperty('overflow')
        }
      }
    }
  }
};
</script>