import { defineStore } from 'pinia';

export const useAlertStore = defineStore('alert', {
  state: () => ({
    alerts: [],
  }),
  actions: {
    showAlert(newMessage, newType) {
      this.message = newMessage;
      this.type = newType;
      this.visible = true;

      setTimeout(() => {
        this.visible = false;
      }, 5000);
    },
    
    closeAlert() {
      this.visible = false;
    },
  },
});