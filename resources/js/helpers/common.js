export default {
    data() {
        return {
            isLoading: false,
            editMode: false,
            searchString: "",
            formData: {},

            permissions: {
                createPermission: true,
                updatePermission: true,
                deletePermission: true
            },
            commonErrors: [],
            listData: {
                data: [],
            },
        };
    },

    methods: {
        getData(url) {
            this.isLoading = true;
            return axios.get(url)
        },

        create() {
            this.editMode = false;
            this.reset();
            $("#modal-default").modal("show");
        },

        store(url, formData) {
            this.isLoading = true;
            return axios.post(url, formData)
        },

        edit(url) {
            this.editMode = true;
            this.reset();
            return axios.get(url) 
        },

        update(url, formData) {
            this.isLoading = true;
            return axios.patch(url, formData)
        },

        delete(url) {
            return axios.delete(url)
        },

        reset() {
            this.formData = {};
            this.commonErrors = [];
        },

        reload() {
            this.searchString = "";
            this.formData = {};
            this.commonErrors = [];
            this.getListData();
          },

        handlePermission(permissions) {
            permissions.forEach((permission) => {
                if (permission.permission === "Create") {
                    this.permissions.createPermission = true;
                } else if (permission.permission === "Update") {
                    this.permissions.updatePermission = true;
                } else if (permission.permission === "Delete") {
                    this.permissions.deletePermission = true;
                }
            });
        }
    }
}
