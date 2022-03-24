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
                <li class="breadcrumb-item">
                  <router-link to="/">Home</router-link>
                </li>
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
                <div class="card-header search">
                  <h3 class="card-title">User List</h3>
                  <ul>
                    <li v-if="permissions.createPermission">
                      <a
                        href="#"
                        @click="create"
                        class="float-right btn btn-sm btn-success"
                        >Add new</a
                      >
                    </li>
                    <li>
                      <a
                        @click.prevent="getListData"
                        class="btn btn-success btn-sm"
                        href="#"
                        >Reload <i class="fas fa-sync-alt"></i
                      ></a>
                    </li>
                    <li>
                      <div>
                        <input
                          v-model="searchString"
                          type="text"
                          placeholder="Search by name..."
                        />
                        <span
                          @click.prevent="getListData"
                          class="btn btn-sm btn-success"
                          >Search</span
                        >
                      </div>
                    </li>
                  </ul>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>SL</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th class="text-center">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <template
                        v-if="
                          listData.hasOwnProperty('data') &&
                          listData.data.length
                        "
                      >
                        <tr
                          v-for="(user, index) in listData.data"
                          :key="user.id"
                        >
                          <td style="width: 25px" class="text-center">
                            {{ listData.meta.from + index }}
                          </td>
                          <td>{{ user.name }}</td>
                          <td>{{ user.email }}</td>
                          <td class="text-center">
                            <a
                              v-if="permissions.updatePermission"
                              href="#"
                              @click.prevent="editData(user.id)"
                              class="btn btn-sm btn-info"
                              ><i class="fas fa-edit"></i
                            ></a>
                            <a
                              v-if="permissions.deletePermission"
                              href="#"
                              @click.prevent="deleteData(user.id)"
                              class="btn btn-sm btn-danger"
                              ><i class="fas fa-trash-alt"></i
                            ></a>
                          </td>
                        </tr>
                      </template>
                    </tbody>
                  </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">
                  <pagination
                    :data="listData"
                    @pagination-change-page="getListData"
                  ></pagination>
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
              <h4 class="modal-title">
                {{ !editMode ? "Add New User" : "Edit User" }}
              </h4>
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
                  placeholder="Enter Password"
                />
                <div
                  class="text-danger"
                  v-if="commonErrors.errors && commonErrors.errors.password"
                >
                  {{ commonErrors.errors.password[0] }}
                </div>
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Role</label>
                <v-select
                  placeholder="Select Role"
                  :options="roles"
                  :reduce="(role) => role.id"
                  label="name"
                  multiple
                  v-model="formData.roles"
                >
                </v-select>
                <div
                  class="text-danger"
                  v-if="commonErrors.errors && commonErrors.errors.role_id"
                >
                  {{ commonErrors.errors.role_id[0] }}
                </div>
              </div>

              <button
                @click.prevent="editMode ? updateData() : storeData()"
                type="submit"
                class="btn btn-success"
              >
                Save Changes
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import swal from "../../services/SweetAlert";
import common from "../../helpers/common";
import toast from '../../services/Notification';

export default {
  mixins: [common],
  data() {
    return {
      roles: [],
    };
  },
  methods: {
    getRoles() {
      this.isLoading = true;
      axios
        .get("/api/users/create")
        .then((response) => {
          this.roles = response.data.data;
          this.isLoading = false;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    getListData(page = 1) {
      this.isLoading = true;
      this.getData(`/api/users?page=${page}&search=${this.searchString}`)
        .then((response) => {
          this.listData = response.data;
          let permissions = response.data.permissions;
          this.handlePermission(permissions);
          this.isLoading = false;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    storeData() {
      this.isLoading = true;
      this.store("/api/users", this.formData)
        .then((response) => {
          this.getListData();
          this.isLoading = false;
          $("#modal-default").modal("hide");
          toast.success('User successfully created!');
        })
        .catch((error) => {
          this.commonErrors = error.response.data;
        });
    },
    editData(id) {
      this.edit(`/api/users/${id}/edit`)
        .then((response) => {
          this.formData = response.data.data;
        })
        .catch((error) => {
          this.commonErrors = error.response.data;
        });
      $("#modal-default").modal("show");
    },
    updateData() {
      this.update(`/api/users/${this.formData.id}`, this.formData)
        .then((response) => {
          this.getListData();
          this.isLoading = false;
          $("#modal-default").modal("hide");
          toast.success('User successfully updated!');
        })
        .catch((error) => {
          this.commonErrors = error.response.data;
        });
    },
    deleteData(id) {
      swal.confirm((result) => {
        if (result.value) {
          this.isLoading = true;
          this.delete(`/api/users/${id}`)
            .then((response) => {
              this.getListData();
              this.isLoading = false;
              toast.success('User successfully Deleted!');
            })
            .catch((error) => {
              this.commonErrors = error.response.data;
            });
        }
      });
    },
  },
  mounted() {
    this.getListData();
    this.getRoles();
  },
};
</script>

<style scoped>
.search ul li {
  float: right;
  list-style: none;
  padding: 0 10px;
}
</style>