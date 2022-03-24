<template>
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <router-link to="/" href="index3.html" class="brand-link">
      <!-- <img src="{{ asset('assets/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8"> -->
      <span class="brand-text font-weight-light">Rental Management</span>
    </router-link>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <!-- <img src="{{ asset('assets/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image"> -->
        </div>
        <div class="info">
          <a href="#" class="d-block">Admin</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul
          class="nav nav-pills nav-sidebar flex-column"
          data-widget="treeview"
          role="menu"
          data-accordion="false"
        >
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <router-link to="/" href="#" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </router-link>
          </li>
          <li v-if="userReadPermission" class="nav-item">
            <router-link class="nav-link" to="/users">
              <i class="nav-icon fas fa-users"></i>
              <p>Users</p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link class="nav-link" to="/roles">
              <i class="nav-icon fas fa-registered"></i>
              <p>Roles</p>
            </router-link>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-copy"></i>
              <p>
                Sample dropdown
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="pages/layout/top-nav.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Top Navigation</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/layout/top-nav-sidebar.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Top Navigation + Sidebar</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <router-link class="nav-link" to="/simple-crud">
              <i class="nav-icon fas fa-plus"></i>
              <p>Simple CRUD</p>
            </router-link>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
</template>

<script>
export default {
  data() {
    return {
      userReadPermission: true,
    };
  },
  mounted() {
    this.getSidebarPermissions();
  },
  methods: {
    getSidebarPermissions() {
      axios
        .get("/api/sidebar-permissions")
        .then((response) => {
          if (response.data) {
            let permissions = response.data;
            permissions.forEach((permission) => {
              if (permission.module === "User") {
                this.userReadPermission = true;
              }
            });
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>
