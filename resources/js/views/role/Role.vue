<template>
  <div class="wrapper">
    <full-page-Loder :isLoading="isLoading"></full-page-Loder>
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Roles</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                  <router-link to="/">Home</router-link>
                </li>
                <li class="breadcrumb-item active">Roles</li>
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
                  <h3 class="card-title">Roles List</h3>
                  <ul>
                    <li>
                      <a
                        href="#"
                        @click="create"
                        class="float-right btn btn-sm btn-success"
                        >Add new</a
                      >
                    </li>
                    <li>
                      <a
                        @click.prevent="reload"
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
                        <th class="text-center">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <template
                        v-if="roles.hasOwnProperty('data') && roles.data.length"
                      >
                        <tr v-for="(role, index) in roles.data" :key="role.id">
                          <td style="width: 25px" class="text-center">
                            {{ roles.meta.from + index }}
                          </td>
                          <td>{{ role.name }}</td>
                          <td class="text-center">
                            <a
                              href="#"
                              @click.prevent="editData(role.id)"
                              class="btn btn-sm btn-info"
                              ><i class="fas fa-edit"></i
                            ></a>
                            <a
                              href="#"
                              @click.prevent="deleteData(role.id)"
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
                    :data="roles"
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
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">
                {{ !editMode ? "Add New role" : "Edit role" }}
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
                <label for="exampleInputEmail1">Role Name</label>
                <input
                  v-model="formData.name"
                  type="text"
                  class="form-control"
                  id="exampleInputEmail1"
                  placeholder="Enter role Name"
                />
                <div v-if="commonErrors.name" class="text-danger">
                  {{ commonErrors.name.toString() }}
                </div>
                <div
                  class="text-danger"
                  v-if="commonErrors.errors && commonErrors.errors.name"
                >
                  {{ commonErrors.errors.name[0] }}
                </div>
              </div>

              <div class="form-group">
                <label>Permission</label>
                <div v-if="commonErrors.permissions" class="text-danger">
                  {{ commonErrors.permissions.toString() }}
                </div>
                <div
                  class="text-danger"
                  v-if="commonErrors.errors && commonErrors.errors.permissions"
                >
                  {{ commonErrors.errors.permissions[0] }}
                </div>
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Module</th>
                      <th>Permission</th>
                    </tr>
                  </thead>

                  <tbody>
                    <tr
                      v-for="(permissions, module) in permissionModules"
                      :key="module"
                    >
                      <td>{{ module }}</td>
                      <td>
                        <div
                          class="form-check form-check-inline"
                          v-for="(permission, permissionKey) in permissions"
                          :key="permissionKey"
                        >
                          <input
                            class="form-check-input"
                            type="checkbox"
                            name="rolePermissions"
                            :id="permission.id"
                            :value="permission.id"
                            v-model="formData.permissions"
                          />
                          <label
                            class="form-check-label"
                            :for="permission.id"
                            >{{ permission.permission }}</label
                          >
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
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
import common from "../../helpers/common";
import swal from "../../services/SweetAlert";
import toast from '../../services/Notification';

export default {
  mixins: [common],
  data() {
    return {
      roles: {
        data: [],
      },
      permissionModules: [],
    };
  },
  methods: {
    getListData(page = 1) {
      this.isLoading = true;
      this.getData(`/api/roles?page=${page}&search=${this.searchString}`)
        .then((response) => {
          this.roles = response.data;
          this.isLoading = false;
        })
        .catch((error) => {
          this.commonErrors = error.response.data;
        });
    },
    getPermissions() {
      this.isLoading = true;
      axios
        .get("/api/roles/create")
        .then((response) => {
          this.permissionModules = response.data;
          this.isLoading = false;
        })
        .catch((error) => {
          this.commonErrors = error.response.data;
        });
    },
    storeData() {
      this.store("/api/roles", this.formData)
        .then((response) => {
          this.isLoading = false;
          $("#modal-default").modal("hide");
          this.getListData();
          toast.success('Data successfully created!');
        })
        .catch((error) => {
          this.commonErrors = error.response.data;
        });
    },
    editData(id) {
      this.editMode = true;
      this.reset();
      this.getRole(id);
      $("#modal-default").modal("show");
    },
    getRole(id) {
      this.isLoading = true;
      axios
        .get(`/api/roles/${id}/edit`)
        .then((response) => {
          this.isLoading = false;
          this.formData = response.data.data;
          this.formData.permissions = response.data.permissions;
        })
        .catch((error) => {
          this.commonErrors = error.response.data;
        });
    },
    updateData() {
      this.isLoading = true;
      this.update(`/api/roles/${this.formData.id}`, this.formData)
        .then((response) => {
          this.getListData();
          $("#modal-default").modal("hide");
          this.isLoading = false;
          toast.success('Data successfully updated!');
        })
        .catch((error) => {
          console.log(error);
          this.commonErrors = error.response.data;
        });
    },
    deleteData(id) {
      swal.confirm((result) => {
        if (result.value) {
          this.isLoading = true;
          this.delete(`/api/roles/${id}`)
            .then((response) => {
              this.isLoading = false;
              toast.success('Data successfully deleted!');
            })
            .catch((error) => {
              console.log(error);
            });
        }
      });
    },
    reset() {
      this.formData = {
        permissions: [],
      };
      this.commonErrors = [];
    }
  },
  mounted() {
    this.getListData();
    this.getPermissions();
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