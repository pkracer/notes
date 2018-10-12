export default {
  computed: {
    selectedColor() {
      if (this.color) return this.color;
      if (this.note && this.note.color) return this.note.color;

      return '';
    },

    backgroundClassObject() {
      let classes = {};

      classes[`bg-${this.selectedColor}-lighter hover:bg-${this.selectedColor}-light`] = true;

      if (this.isUsingDefaultColor) {
        classes[`bg-white hover:bg-grey-lightest`] = true;
      }

      return classes;
    },

    buttonClassObject() {
      let classes = {};

      classes[`hover:bg-${this.selectedColor}`] = true;

      if (this.isUsingDefaultColor) {
        classes[`hover:bg-grey-lightest`] = true;
      }

      return classes;
    },

    isUsingDefaultColor() {
      return this.selectedColor === 'white';
    }
  }
};