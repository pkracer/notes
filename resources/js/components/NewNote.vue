<template>
    <on-click-outside :do="saveAndClose">
        <div class="relative shadow rounded" :class="backgroundClassObject">
            <input v-show="focused" v-model="title" @keyup.enter="focusOnBody" type="text" class="mt-2 outline-none w-full bg-transparent px-3 py-2 font-bold" placeholder="Title"/>
            <textarea ref="body" @keyup="resizeTextArea('body')" @focus="focused = true" v-model="body" class="w-full outline-none bg-transparent px-3 py-2 resize-none" placeholder="Take a note" rows="1"></textarea>
            <div v-show="focused" class="w-full flex items-center justify-between px-3 py-2">
                <color-picker-button @color-toggled="setColor" :color="color"></color-picker-button>
                <button @click="saveAndClose" class="bg-transparent px-3 py-2 rounded text-grey-darkest" :class="buttonClassObject">Close</button>
            </div>
        </div>
    </on-click-outside>
</template>

<script>
import OnClickOutside from './OnClickOutside';
import ColorPickerButton from './ColorPickerButton';
import NoteColors from '../mixins/NoteColors';
import ResizeTextArea from '../mixins/ResizeTextArea';

export default {
  components: { OnClickOutside, ColorPickerButton },

  mixins: [ NoteColors, ResizeTextArea ],

  data: () => ({
    focused: false,
    title: '',
    body: '',
    color: 'white'
  }),

  computed: {
    noteWasTaken() {
      return this.title !== '' || this.body !== '';
    },
  },

  methods: {
    saveAndClose() {
      if (this.noteWasTaken) {
        let now = moment.utc().format('YYYY-MM-DD HH:mm:ss');

        this.$emit('note-taken', {
          title: this.title,
          body: this.body,
          color: this.color,
          created_at: now,
          updated_at: now,
        });
      }

      this.resetForm();
    },

    resetForm() {
      this.focused = false;
      this.title = '';
      this.body = '';
      this.color = 'white';
    },

    setColor(color) {
      this.color = color;
    },

    focusOnBody() {
      this.$refs.body.focus();
    }
  }
};
</script>