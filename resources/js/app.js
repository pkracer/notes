require('./bootstrap');

import Vue from 'vue';
import PortalVue from 'portal-vue';
Vue.use(PortalVue);

import Note from './components/Note';
import NewNote from './components/NewNote';
import { findIndex } from 'lodash';

const app = new Vue({
  el: '#app',

  components: { NewNote, Note },

  data: {
    notes
  },

  methods: {
    addNoteToList(note) {
      this.notes.unshift(note)
    },

    handleNoteCreation(note) {
      let index = findIndex(this.notes, existingNote => {
        return !existingNote.hasOwnProperty('id')
          && existingNote.title === note.title
          && existingNote.body === note.body
          && existingNote.color === note.color
      });

      this.notes.splice(index, 1, note)
    },

    handleNoteUpdate(note) {
      let index = findIndex(this.notes, existingNote => {
        return existingNote.hasOwnProperty('id')
          && existingNote.id === note.id
      });

      this.notes.splice(index, 1, note)
    },

    handleNoteDeletion(note) {
      let index = findIndex(this.notes, existingNote => {
        return existingNote.hasOwnProperty('id')
          && existingNote.id === note.id
      });

      this.notes.splice(index, 1)
    }
  }
});
