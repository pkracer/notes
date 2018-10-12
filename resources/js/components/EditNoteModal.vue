<template>
    <modal :show="show" @close="saveAndClose">
        <div class="w-full md:w-1/3">
            <div class="rounded shadow-lg" :class="backgroundClassObject">
                <div class="px-4">
                    <input v-model="title"
                           @keyup.enter="focusOnBody"
                           class="mt-2 outline-none w-full bg-transparent px-3 py-2 font-bold"
                           placeholder="Title"
                    />
                    <textarea ref="body"
                              v-model="body"
                              @keyup="resizeTextArea('body')"
                              @focus="focused = true"
                              class="w-full outline-none bg-transparent px-3 py-2 resize-none"
                              placeholder="Note"
                              rows="1"
                    ></textarea>

                    <p class="text-right italic text-sm mb-2">Edited: {{ lastEdit }}</p>
                </div>

                <div class="w-full flex items-center justify-between px-4 py-2 shadow-inner">
                    <div>
                        <color-picker-button @color-toggled="setColor" :color="color"></color-picker-button>
                        <button @click="remove" class="bg-transparent px-3 py-2 rounded text-grey-darkest"><i class="fas fa-trash"></i></button>
                    </div>
                    <button @click="saveAndClose" class="bg-transparent px-3 py-2 text-grey-darkest rounded" :class="buttonClassObject">Close</button>
                </div>
            </div>
        </div>
    </modal>
</template>

<script>
import Modal from './Modal';
import ColorPickerButton from './ColorPickerButton';
import NoteColors from '../mixins/NoteColors';
import ResizeTextArea from '../mixins/ResizeTextArea';

export default {
  props: {
    show: { default: false },
    note: { required: true }
  },

  components: { Modal, ColorPickerButton },

  mixins: [ NoteColors, ResizeTextArea ],

  data: () => ({
    title: '',
    body: '',
    color: '',
  }),

  mounted() {
    this.title = this.note.title;
    this.body = this.note.body;
    this.color = this.note.color;
  },

  computed: {
    lastEdit() {
      return moment.utc(this.note.updated_at).format('MMM Do, h:mm A');
    }
  },

  methods: {
    close() {
      this.$emit('close');
    },

    remove() {
      this.$emit('deleted', this.note);
    },

    save() {
      let now = moment.utc().format('YYYY-MM-DD HH:mm:ss');

      this.$emit('updated', {
        id: this.note.id,
        title: this.title,
        body: this.body,
        color: this.color,
        created_at: now,
        updated_at: now,
      });
    },

    setColor(color) {
      this.color = color
    },

    saveAndClose() {
      if (
        this.title !== this.note.title
        || this.body !== this.note.body
        || this.color !== this.note.color
      ) {
        this.save();
      }

      this.close();
    },

    focusOnBody() {
      this.$refs.body.focus();
    }
  },

  watch: {
    show() {
      if (this.show) {
        this.$nextTick(() => {
          this.resizeTextArea('body');
        });
      }
    }
  }
};
</script>