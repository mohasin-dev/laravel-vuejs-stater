<template>
  <div class="login-box">
    <full-page-Loder :isLoading="isLoading"></full-page-Loder>
    <div class="login-logo">
      <a href="../../index2.html"><b>Admin</b>LTE</a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">Sign in to start your sessionn</p>

        <div v-if="commonErrors.data">
          <div class="alert alert-danger" role="alert">
            {{ commonErrors.data.error }}
          </div>
        </div>

        <form>
          <div class="input-group mb-3">
            <input
              type="email"
              @input="SET_LOGIN_FORM_EMAIL($event.target.value)"
              :value="loginForm.email"
              class="form-control"
              placeholder="Email"
            />
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input
              type="password"
              :value="loginForm.password"
              @input="SET_LOGIN_FORM_PASSWORD"
              class="form-control"
              placeholder="Password"
            />
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-8">
              <div class="icheck-primary">
                <input type="checkbox" id="remember" />
                <label for="remember"> Remember Me </label>
              </div>
            </div>
            <!-- /.col -->
            <div class="col-4">
              <button
                type="submit"
                class="btn btn-primary btn-block"
                @click.prevent="auth"
              >
                Sign In
              </button>
            </div>
            <!-- /.col -->
          </div>
        </form>

        <p class="mb-1">
          <a href="forgot-password.html">I forgot my password</a>
        </p>
        <p class="mb-0">
          <a href="register.html" class="text-center"
            >Register a new membership</a
          >
        </p>
      </div>
      <!-- /.login-card-body -->
    </div>
  </div>
</template>

<script>
import { setAuthorization } from "../../helpers/halpers";
import { mapState, mapMutations, mapActions } from "vuex";
// import FullPageLoader from '../../components/partials/FullPageLoder'

export default {
  // components: {FullPageLoader},
  name: "login",
  data() {
    return {
      error: null,
    };
  },
  methods: {
    ...mapMutations("auth", [
      "SET_LOGIN_FORM_EMAIL",
      "SET_LOGIN_FORM_PASSWORD",
      "SET_COMMON_ERRORS",
    ]),
    ...mapActions("auth", ["login"]),
    auth() {
      this.login();
    },
    getConstraints() {
      return {
        email: {
          presence: { message: "Email is required" },
          email: true,
        },
        password: {
          presence: { message: "Password is required" },
        },
      };
    },
  },
  computed: {
    ...mapState("helpers", ["isLoading", "commonErrors"]),
    ...mapState("auth", ["loginForm"]),
    authError() {
      return this.$store.getters.authError;
    },
  },
};
</script>

<style scoped>
</style>

