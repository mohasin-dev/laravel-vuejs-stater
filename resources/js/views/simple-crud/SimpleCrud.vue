<template>
  <div class="wrapper">
    <full-page-Loder :isLoading="isLoading"></full-page-Loder>
    <div class="content-wrapper">
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Simple CRUD</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                  <router-link to="/">Home</router-link>
                </li>
                <li class="breadcrumb-item active">Simple CRUD</li>
              </ol>
            </div>
          </div>
        </div>
      </section>

      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header search">
                  <h3 class="card-title">CRUD List</h3>
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
                          v-for="(data, index) in listData.data"
                          :key="data.id"
                        >
                          <td style="width: 25px" class="text-center">
                            {{ listData.meta.from + index }}
                          </td>
                          <td>{{ data.name }}</td>
                          <td>{{ data.email }}</td>
                          <td class="text-center">
                            <a
                              v-if="permissions.updatePermission"
                              href="#"
                              @click.prevent="editData(data.id)"
                              class="btn btn-sm btn-info"
                              ><i class="fas fa-edit"></i
                            ></a>
                            <a
                              v-if="permissions.deletePermission"
                              href="#"
                              @click.prevent="deleteData(data.id)"
                              class="btn btn-sm btn-danger"
                              ><i class="fas fa-trash-alt"></i
                            ></a>
                          </td>
                        </tr>
                      </template>
                      <template v-else>
                        <tr>
                          <td colspan="4">
                            <p class="text-center">No data found</p>
                          </td>
                        </tr>
                      </template>
                    </tbody>
                  </table>
                </div>
                <div class="card-footer clearfix">
                  <pagination
                    :data="listData"
                    @pagination-change-page="getListData"
                  ></pagination>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <div class="modal" id="modal-default">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">
                {{ ! editMode ? "Add" : "Edit" }}
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
                <label for="exampleInputEmail1">Mobile</label>
                <input
                  v-model="formData.mobile"
                  type="text"
                  class="form-control"
                  id="exampleInputEmail1"
                  placeholder="Enter Mobile"
                />
                <div
                  class="text-danger"
                  v-if="commonErrors.errors && commonErrors.errors.mobile"
                >
                  {{ commonErrors.errors.mobile[0] }}
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
import common from "../../helpers/common";
import swal from "../../services/SweetAlert";
import toast from '../../services/Notification';

export default {
  mixins: [common],
  data() {
    return {};
  },
  methods: {
    getListData(page = 1) {
      this.getData(`/api/simple-crud?page=${page}&search=${this.searchString}`)
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
      this.store("/api/simple-crud", this.formData)
        .then((response) => {
          this.getListData();
          this.isLoading = false;
          $("#modal-default").modal("hide");
          toast.success('Data successfully created!');
        })
        .catch((error) => {
          this.commonErrors = error.response.data;
        });
    },
    editData(id) {
      this.edit(`/api/simple-crud/${id}/edit`)
        .then((response) => {
          this.formData = response.data.data;
        })
        .catch((error) => {
          this.commonErrors = error.response.data;
        });
      $("#modal-default").modal("show");
    },
    updateData() {
      this.update(`/api/simple-crud/${this.formData.id}`, this.formData)
        .then((response) => {
          this.getData();
          $("#modal-default").modal("hide");
          this.isLoading = false;
          toast.success('Data successfully updated!');
        })
        .catch((error) => {
          this.commonErrors = error.response.data;
        });
    },
    deleteData(id) {
      swal.confirm((result) => {
        if (result.value) {
          this.isLoading = true;
          this.delete(`/api/simple-crud/${id}`)
            .then((response) => {
              this.isLoading = false;
              toast.success('Data successfully Deleted!');
            })
            .catch((error) => {
              console.log(error);
            });
        }
      });
    },
  },
  mounted() {
    this.getListData();
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