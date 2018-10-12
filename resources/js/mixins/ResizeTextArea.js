export default {
  methods: {
    resizeTextArea(ref) {
      let textArea = this.$refs[ref];
      textArea.style.height = `${Math.max(textArea.scrollHeight, textArea.clientHeight)}px`;
    },
  }
};