<template>
  <div>
    <Topbar v-if="currentUser" />
    <Sidebar v-if="currentUser" />
    <router-view></router-view>
    <Footer v-if="currentUser" />
  </div>
</template>


<script>
import Topbar from "./partials/Topbar";
import Sidebar from "./partials/Sidebar";
import Footer from "./partials/Footer";
import { mapState, mapMutations, mapActions } from "vuex";

export default {
  name: "App",
  components: {
    Topbar,
    Sidebar,
    Footer,
  },
  data() {
    return {};
  },
  computed: {
    ...mapState("auth", ["currentUser"]),
  },
};
</script>
