<template>
    <div class="px-4 items-start">
        <div @click="toggleEdit" class="cursor-pointer relative w-full shadow rounded px-4 py-2" :class="backgroundClassObject">
            <div v-show="syncing" class="absolute pin-t pin-r w-8 h-8 flex items-center justify-center" :class="backgroundClassObject">
                <i class="fas fa-sync fa-spin text-sm"></i>
            </div>
            <p v-show="hasTitle" v-text="note.title" class="break-word font-bold mb-4"></p>
            <p v-text="note.body" class="break-word"></p>
        </div>

        <edit-note-modal
            :show="editing"
            :note="note"
            @close="editing = false"
            @updated="handleNoteUpdate"
            @deleted="handleNoteDelete"
        ></edit-note-modal>
    </div>
</template>

<script>
import EditNoteModal from './EditNoteModal';
import ColorPickerButton from './ColorPickerButton';
import NoteColors from '../mixins/NoteColors';

export default {
  props: {
    note: { required: true }
  },

  components: { EditNoteModal, ColorPickerButton },

  mixins: [ NoteColors ],

  data: () => ({
    syncing: false,
    editing: false
  }),

  mounted() {
    if (!this.exists) {
      this.create();
    }
  },

  computed: {
    exists() {
      return this.note.hasOwnProperty('id');
    },

    hasTitle() {
      return this.note.title !== '';
    },
  },

  methods: {
    toggleEdit() {
      if (this.syncing) return;
      this.editing = true
    },

    setColor(color) {
      this.color = color;
    },

    create() {
      if (this.syncing) return;

      this.syncing = true;

      axios.post(`/api/notes`, this.note)
        .then(({ data: note }) => {
          this.$emit('created', note);
          this.syncing = false;
        });
    },

    handleNoteUpdate(note) {
      if (this.syncing) return;

      this.syncing = true;

      axios.patch(`/api/notes/${this.note.id}`, note)
        .then(({ data: note }) => {
          this.$emit('updated', note);
          this.syncing = false;
        });
    },

    handleNoteDelete(note) {
      if (this.syncing) return;

      this.syncing = true;

      axios.delete(`/api/notes/${this.note.id}`);

      this.$emit('deleted', note);
    }
  }
};
</script>