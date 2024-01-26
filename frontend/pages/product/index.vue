<template>
  <div>
    <div class="row">
      <div class="col-xl-12">
        <div class="card">
          <div class="card-header">
            <h3>List Product</h3>
          </div>
          <div class="card-body">
            <div class="row mb-2">
              <div class="col-xl-6">
                <a class="btn btn-success btn-icon-split" v-on:click="newProduct()">
                  <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                  </span>
                  <span class="text">Make a new product</span>
                </a>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12 col-md-4">
                <div id="tickets-table_length" class="dataTables_length">
                  <label class="d-inline-flex align-items-center">
                    Show&nbsp;
                    <b-form-select id="rounded" v-model="table.perPage" size="sm" :options="table.pageOptions">
                    </b-form-select>
                    &nbsp;data
                  </label>
                </div>
              </div>
              <!-- Search -->
              <div class="col-sm-12 col-md-8">
                <div id="tickets-table_filter" class="dataTables_filter text-md-end">
                  <label class="d-inline-flex align-items-center">
                    Search:
                    <b-form-input id="rounded" type="search" @input="debounceSearch" v-model="table.search"
                      class="form-control form-control-sm ml-2" autocomplete="off"></b-form-input>
                  </label>
                </div>
              </div>
              <!-- End search -->
            </div>
            <div class="row">
              <div class="col-xl-12">
                <!-- <div id="custom-loading-container" v-show="table.loading"
                  style="width:100%;height:250px;background-color:#16A75C;">
                </div> -->
                <b-table responsive="sm" :fields="table.fields" :items="table.items" :current-page="table.currentPage"
                  :busy.sync="table.loading" :sort-by.sync="table.sortBy" :sort-desc.sync="table.sortDesc"
                  empty-filtered-text="Product not found" empty-text="No Data" :show-empty="true" striped hover bordered>
                  <template v-slot:cell(number)="{ index }">
                    {{ (index + 1 + number) }}
                  </template>
                  <template v-slot:cell(actions)="{ item }">
                    <b-button v-on:click="updateProduct(item.id)" size="sm" style="margin-top: 0.2rem;" variant="warning">
                      <i class="fas fa-pen"></i>
                    </b-button>
                    <b-button v-on:click="deleteProduct(item.id, item.name)" size="sm" style="margin-top: 0.2rem;" variant="danger">
                      <i class="fas fa-trash"></i>
                    </b-button>
                  </template>
                </b-table>
              </div>
            </div>
          </div>
          <div class="card-footer">
            <div class="row">
              <div class="col">
                <div class="dataTables_paginate paging_simple_numbers float-end">
                  <ul class="pagination pagination-rounded mb-0">
                    <!-- pagination -->
                    <b-pagination v-model="table.currentPage" :total-rows="table.totalRows" :per-page="table.perPage">
                    </b-pagination>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <b-modal id="modal-create-form" centered size="lg" title="Make a new product" title-class="font-27" hide-footer
        no-close-on-backdrop>
        <div class="row">
          <div class="col-12">
            <product-form ref="revisi" :items="form" @input="submitCreate"></product-form>
          </div>
        </div>
      </b-modal>
      <b-modal id="modal-update-form" centered size="lg" title="Update a product" title-class="font-27" hide-footer
        no-close-on-backdrop>
        <div class="row">
          <div class="col-12">
            <product-form ref="revisi" :items="form" @input="submitUpdate"></product-form>
          </div>
        </div>
      </b-modal>
    </div>
  </div>
</template>

<script>
import ProductForm from '~/components/Product/form'
import Swal from 'sweetalert2'

export default {
  name: 'Product',
  layout: 'base',
  middleware: 'authentication',
  components: {
    ProductForm
  },
  data() {
    return {
      number: 0,
      table: {
        fields: [
          {
            key: 'number',
            label: 'Number',
            sortable: false,
          },
          {
            key: 'name',
            label: 'Product Name',
            sortable: true,
          },
          {
            key: 'description',
            label: 'Description',
            sortable: true,
          },
          {
            key: 'price',
            label: 'Price',
            sortable: true,
          },
          {
            key: 'stock',
            label: 'Stock',
            sortable: false,
          },
          {
            key: 'actions',
            label: 'Actions',
            sortable: false,
          },
        ],
        items: [],
        totalRows: null,
        currentPage: 1,
        perPage: 5,
        pageOptions: [5, 10, 15, 100],
        sortBy: 'id',
        sortDesc: false,
        search: null,
        loading: true,
      },
      form: {
        id: null,
        name: null,
        description: null,
        price: null,
        stock: null,
      }
    }
  },
  methods: {
    async getListProduct() {
      this.table.loading = true
      let param = {
        page: this.table.currentPage,
        search: this.table.search,
        perPage: this.table.perPage,
        order_direction: this.table.sortDesc,
        order_column: this.table.sortBy,
      }
      this.$store.dispatch("product/index", { params: param })
        .then((resp) => {
          this.table.items = resp.data.data
          this.table.totalRows = resp.data.total
          this.table.currentPage = resp.data.current_page
          this.table.perPage = resp.data.per_page
          this.table.loading = false
        })
        .catch((error) => {
          this.table.loading = false
        });
    },
    debounceSearch(val) {
      clearTimeout(this.debounce)
      this.debounce = setTimeout(() => {
        if (this.table.currentPage !== 1) {
          this.table.currentPage = 1
        } else {
          this.getListProduct()
        }
      }, 600)
    },
    submitCreate(productForm) {
      let form = productForm;
      this.$store
        .dispatch("product/store", { form })
        .then((resp) => {
          this.getListProduct();
          this.$bvModal.hide('modal-create-form')
          this.form.id = null;
          this.form.name = null;
          this.form.price = null;
          this.form.description = null;
          this.form.stock = null;
          Swal.fire("Success!", "New product has been created", "success");
        })
        .catch((error) => {
        });
    },
    submitUpdate(productForm) {
      let form = productForm;
      this.$store
        .dispatch("product/update", { id: this.form.id, form })
        .then((resp) => {
          this.getListProduct();
          this.$bvModal.hide('modal-update-form')
          this.form.id = null;
          this.form.name = null;
          this.form.price = null;
          this.form.description = null;
          this.form.stock = null;
          Swal.fire("Success!", "Product has been updated", "success");
        })
        .catch((error) => {
        });
    },
    updateProduct(id) {
      this.form.id = id
      this.getProduct(id)
      this.$bvModal.show('modal-update-form')
    },
    getProduct(id) {
      this.$store.dispatch("product/getProduct", { id: id })
        .then((resp) => {
          this.form.name = resp.data.name
          this.form.price = resp.data.price
          this.form.description = resp.data.description
          this.form.stock = resp.data.stock
        })
        .catch((error) => {
        });
    },
    deleteProduct(id, name) {
      Swal.fire({
        title: "Delete product?",
        text: "Are you sure you want to delete" + name + "?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#16A75C",
        cancelButtonColor: "#E53935",
        confirmButtonText: "Yes",
        cancelButtonText: "Cancel"
      }).then(result => {
        if (result.value) {
          this.$store.dispatch("product/deleteProduct", { id: id })
            .then((resp) => {
              Swal.fire("Success!", "Product has been deleted!", "success")
                .then((result) => {
                  this.getListProduct();
                });
            })
            .catch((error) => {
              Swal.fire("Error!", "Failed to delete product from your cart", "danger");
            });
        }
      });
    },
    newProduct() {
      this.$bvModal.show('modal-create-form')
    },
  },
  watch: {
    'table.currentPage': function (newVal, oldVal) {
      if (this.table.currentPage !== 1) {
        this.number = this.table.perPage * (this.table.currentPage - 1)
      }
      if (this.table.currentPage == 1) {
        this.number = 0
      }
      this.getListProduct()
    },
    'table.perPage': function (newVal, oldVal) {
      if (this.table.currentPage == 1) {
        this.getListProduct()
      } else {
        this.table.currentPage = 1
      }
    },
    'table.sortDesc': function (newVal, oldVal) {
      if (this.table.currentPage == 1) {
        this.getListProduct()
      } else {
        this.table.currentPage = 1
      }
    },
    'table.sortBy': function (newVal, oldval) {
      if (this.table.currentPage == 1) {
        this.getListProduct()
      } else {
        this.table.currentPage = 1
      }
    },
  },
  async created() {
    await this.getListProduct();
  },
}
</script>
