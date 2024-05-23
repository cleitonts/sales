<template>
  <div v-if="messages.length" class="notification-wrapper">
    <the-notification
      v-for="notification in messages"
      :key="notification.id"
      :last-id="notification.id"
      :text="notification.text"
      :timeout="notification.time"
      :type="notification.type"
    >
    </the-notification>
  </div>
</template>
<script>
import { defineComponent } from "vue";
import TheNotification from "./TheNotification.vue";
import { useNotificationStore } from "~/stores/";

export default defineComponent({
  components: { TheNotification },
  // name: "TheNotifications",
  setup: async function () {
    const notificationStore = useNotificationStore();
    const messages = notificationStore.messages;
    return { messages };
  },
});
</script>

<style lang="scss">
.notification-wrapper {
  position: fixed;
  right: 10px;
  bottom: 30px;
  z-index: 2000;

  & .v-overlay--contained {
    position: relative;
    height: auto;
  }

  & .v-overlay__content {
    position: relative;
  }
}
</style>
