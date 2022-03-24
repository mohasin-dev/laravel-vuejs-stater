<template>
  <div class="wrapper">
    <full-page-Loder :isLoading="isLoading"></full-page-Loder>
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Users</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Users</li>
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
                  <h3 class="card-title">User List</h3>
                  <a @click="create" class="float-right btn btn-sm btn-success"
                    >Add new</a
                  >
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th style="width: 10px">Id</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="user in users" :key="user.id">
                        <td>{{ user.id }}</td>
                        <td>{{ user.name }}</td>
                        <td>{{ user.email }}</td>
                        <td>
                          <a href="#" class="btn btn-sm btn-info">Edit</a>
                          <a
                            href="#"
                            @click.prevent="deleteData(user.id)"
                            class="btn btn-sm btn-danger"
                            >Delete</a
                          >
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">
                  <ul class="pagination pagination-sm m-0 float-right">
                    <li class="page-item">
                      <a class="page-link" href="#">&laquo;</a>
                    </li>
                    <li class="page-item">
                      <a class="page-link" href="#">1</a>
                    </li>
                    <li class="page-item">
                      <a class="page-link" href="#">2</a>
                    </li>
                    <li class="page-item">
                      <a class="page-link" href="#">3</a>
                    </li>
                    <li class="page-item">
                      <a class="page-link" href="#">&raquo;</a>
                    </li>
                  </ul>
                </div>
              </div>
              <!-- /.card -->
            </div>
          </div>
        </div>
        <!-- /.container-fluid -->
      </section>
      <!-- /.content -->

      <div class="modal" id="modal-default">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Add New User</h4>
              <button
                type="button"
                class="close"
                data-dismiss="modal"
                aria-label="Close"
              >
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                {{ commonErrors }}
                <label for="exampleInputEmail1">Full Name</label>
                <input
                  v-model="formData.name"
                  type="text"
                  class="form-control"
                  id="exampleInputEmail1"
                  placeholder="Enter Full Name"
                />
                <div
                  class="text-danger"
                  v-if="commonErrors.errors && commonErrors.errors.name"
                >
                  {{ commonErrors.errors.name[0] }}
                </div>
              </div>

              <div class="form-group">
                <label for="exampleInputEmail1">Email</label>
                <input
                  v-model="formData.email"
                  type="text"
                  class="form-control"
                  id="exampleInputEmail1"
                  placeholder="Enter Email"
                />
                
                <!-- <div
                  class="text-danger mb-2"
                  v-if="!$v.formData.email.required"
                >
                  Email is required
                </div>
                <div class="text-danger mb-2" v-if="!$v.formData.email.email">
                  Input must be email
                </div> -->
                <div
                  class="text-danger"
                  v-if="commonErrors.errors && commonErrors.errors.email"
                >
                  {{ commonErrors.errors.email[0] }}
                </div>
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Password</label>
                <input
                  v-model="formData.password"
                  type="text"
                  class="form-control"
                  id="exampleInputEmail1"
                  placeholder="Enter Email"
                />
                <!-- <div
                  class="text-danger mb-2"
                  v-if="!$v.formData.password.required"
                >
                  Password is required
                </div> -->
                <div
                  class="text-danger"
                  v-if="commonErrors.errors && commonErrors.errors.password"
                >
                  {{ commonErrors.errors.password[0] }}
                </div>
              </div>

              <button
                @click.prevent="storeData"
                type="submit"
                class="btn btn-sm btn-info"
              >
                Save
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapMutations, mapActions, mapState } from "vuex";

export default {
  data() {
    return {
      formData: {
        name: "",
        email: "",
        password: "",
      },
      errors: "",
    };
  },
  methods: {
    ...mapState("user", ["SET_IS_ACTIVE"]),
    ...mapMutations("helpers", ["SET_IS_ACTIVE"]),
    ...mapActions("user", ["store", "all", "delete"]),
    create() {
      // this.editMode = false;
      // this.form.reset();
      // this.form.clear();
      $("#modal-default").modal("show");
    },
    storeData() {
      alert("sdfdf");
      this.store(this.formData);
      if (!this.commonErrors) {
        //$("#modal-default").modal("hide");
      }
    },
    deleteData(id) {
      this.delete(id);
    },
  },
  mounted() {
    this.all();
  },
  computed: {
    ...mapState("helpers", ["isLoading", "commonErrors"]),
    ...mapState("user", ["users"]),
  },
};
</script>

<style scoped>
</style>