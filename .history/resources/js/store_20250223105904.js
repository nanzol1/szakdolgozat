import { defineStore } from 'pinia';

export const useAlertStore = defineStore('alert', {
  state: () => ({
    alerts: [],
  }),
  actions: {
    showAlert(newMessage, newType) {
      const alert = {
        id:Date.now(),
        message:newMessage,
        type:newType,
      };

      this.alert.push(alerts);

      setTimeout(() => {
        this.removeAlert(alert.id);
      }, 5000);
    },
    
    closeAlert() {
      this.visible = false;
    },
  },
});