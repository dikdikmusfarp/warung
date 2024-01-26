<template>
  <div>
    <div class="row">
      <div class="col-xl-6">
        <form class="d-none d-sm-inline-block form-inline navbar-search">
          <div class="input-group">
            <input @input="debounceSearch" type="text" class="form-control bg-light border-1 small"
              placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2" v-model="table.search">
            <div class="input-group-append">
              <button class="btn btn-primary" type="button">
                <i class="fas fa-search fa-sm"></i>
              </button>
            </div>
          </div>
        </form>
      </div>
      <div class="col-xl-6">
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text">Show</span>
          </div>
          <select v-model="table.perPage">
            <option v-for="option in table.pageOptions" :key="option.value" :value="option.value">
              {{ option.value }}
            </option>
          </select>
          <div class="input-group-append">
            <label class="input-group-text" for="inputGroupSelect02">Data</label>
          </div>
        </div>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col-xl-3" v-for="(item, idx) in table.items" :key="idx" v-if="(table.items).length > 0">
        <div class="card">
          <img class="card-img-top" src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="Card image cap">
          <div class="card-body">
            <h5 class="card-title">{{ item.name }}</h5>
            <p class="card-text text-danger">only {{ item.stock }}</p>
            <p class="card-text">{{ (item.description).slice(0, 70) }}...</p>
            <a v-if="role==2" v-on:click="addToCart(item.id, item.name)" class="btn btn-primary"><i class="fas fa-cart-plus"></i> add</a>
          </div>
        </div>
      </div>
    </div>
    <div class="row mt-3">
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
</template>

<script>
import Swal from 'sweetalert2'

export default {
  data() {
    return {
      number: 0,
      table: {
        items: [],
        totalRows: null,
        currentPage: 1,
        perPage: 5,
        pageOptions: [
          { value: 5, label: 'Option 1' },
          { value: 10, label: 'Option 2' },
          { value: 20, label: 'Option 3' },
        ],
        sortBy: 'id',
        sortDesc: false,
        search: null,
        loading: true,
      },
      form: {
        product_id: null,
      },
      role: null,
      user: null,
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
    addToCart(id, name) {
      Swal.fire({
        title: "Add to cart?",
        text: "Are you sure you want to add" + name + "to your cart?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#16A75C",
        cancelButtonColor: "#E53935",
        confirmButtonText: "Yes",
        cancelButtonText: "Cancel"
      }).then(result => {
        if (result.value) {
          this.form.product_id = id
          let form = this.form
          this.$store
            .dispatch("cart/store", { form })
            .then((resp) => {
              Swal.fire("Success!", "Product has been added!", "success")
                .then((result) => {
                  this.form.product_id = null
                });
            })
            .catch((error) => {
              Swal.fire("Error!", "Failed to add product to your cart", "danger");
            });
        }
      });
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
    this.user = this.$store.state.auth.user
    if (this.user) {
      this.role = this.user.role_id
    }
    await this.getListProduct();
  },
}
</script>
