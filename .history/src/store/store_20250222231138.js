import { defineStore } from 'pinia';

// Alert store létrehozása
export const useAlertStore = defineStore('alert', {
  state: () => ({
    visible: false, // Az alert láthatósága
    message: '',    // Az üzenet szövege
    type: '',       // Az alert típusa (pl. success, error)
  }),
  actions: {
    // Alert megjelenítése
    showAlert(newMessage, newType) {
      this.message = newMessage;
      this.type = newType;
      this.visible = true;

      // Automatikusan eltűnik 3 másodperc után
      setTimeout(() => {
        this.visible = false;
      }, 3000);
    },
    // Alert bezárása
    closeAlert() {
      this.visible = false;
    },
  },
});