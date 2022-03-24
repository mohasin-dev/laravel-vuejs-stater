<template>
  <div>
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Users List</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Users List</li>
            </ol>
          </div>
        </div>
      </div>
      <!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <a class="btn btn-sm btn-primary float-right" href="#">Create New User</a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <template v-if="!users.length">
                      <tr>
                        <td colspan="4" class="text-center">
                          No Users Available
                        </td>
                      </tr>
                    </template>
                    <template v-else>
                      <tr v-for="user in users" :key="user.id">
                        <td>{{ user.id }}</td>
                        <td>{{ user.name }}</td>
                        <td>{{ user.email }}</td>
                        <td>
                          <router-link class="btn btn-sm btn-info" :to="`/users/${user.id}`"
                            ><i class="fas fa-eye"></i>
                            </router-link
                          >
                          <router-link class="btn btn-sm btn-danger" :to="`/users/${user.id}`"
                            ><i class="fas fa-trash-alt"></i></router-link
                          >
                          <router-link :to="`/users/${user.id}`"
                            >View</router-link
                          >
                        </td>
                      </tr>
                    </template>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <!-- <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-right">
                  <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                  <li class="page-item"><a class="page-link" href="#">1</a></li>
                  <li class="page-item"><a class="page-link" href="#">2</a></li>
                  <li class="page-item"><a class="page-link" href="#">3</a></li>
                  <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                </ul>
              </div> -->
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
</template>

<script>
export default {
  name: "list",
  mounted() {
    console.log(this.users);
    if (this.users.length) {
      return;
    }

    this.$store.dispatch("getUsers");
  },
  computed: {
    users() {
      return this.$store.getters.users;
    },
  },
};
</script>

<style scoped>
.btn-wrapper {
  text-align: right;
  margin-bottom: 20px;
}
</style>
