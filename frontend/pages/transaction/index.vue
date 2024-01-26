<template>
  <div>
    <div class="row">
      <div class="col-xl-12">
        <div class="card">
          <div class="card-header">
            <h3>List Transaction</h3>
          </div>
          <div class="card-body">
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
                  empty-filtered-text="Transaction not found" empty-text="No Data" :show-empty="true" striped hover
                  bordered>
                  <template v-slot:cell(number)="{ index }">
                    {{ (index + 1 + number) }}
                  </template>
                  <template v-slot:cell(actions)="{ item }">
                    <b-button v-on:click="showTransaction(item.id)" size="sm" style="margin-top: 0.2rem;" variant="info">
                      <i class="fas fa-eye"></i>
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
      <b-modal id="modal-list-transaction" centered size="lg" title="List product on transaction" title-class="font-27"
        hide-footer no-close-on-backdrop>
        <div class="row">
          <div class="col-12">
            <table class="table table-bordered borderBlack mb-2">
              <thead>
                <tr class="bg-primary">
                  <th colspan="5" class="text-white">DIKDIK MUSFAR</th>
                </tr>
                <tr class="softGreen">
                  <th width="10%" scope="col">No</th>
                  <th width="40%" scope="col">Product Name</th>
                  <th width="20%" scope="col">Price</th>
                  <th width="10%" scope="col">Amount</th>
                  <th width="20%" scope="col">Total</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, idx) in transaction.detail" :key="idx" v-if="(transaction.detail).length > 0">
                  <th scope="row">{{ idx + 1 }}</th>
                  <td>{{ item.name }}</td>
                  <td>Rp. {{ item.price_at }}</td>
                  <td>{{ item.amount }}</td>
                  <td>Rp. {{ item.total }}</td>
                </tr>
                <tr class="bg-primary">
                  <th colspan="4" class="text-white text-center">Grand Total</th>
                  <th class="text-white">Rp. {{ transaction.sale ? transaction.sale.total : 0 }}</th>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </b-modal>
    </div>
  </div>
</template>

<script>
import ProductForm from '~/components/Product/form'
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
            label: 'Customer',
            sortable: true,
          },
          {
            key: 'total',
            label: 'Total Price',
            sortable: true,
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
      transaction: {
        sale: null,
        detail: []
      }
    }
  },
  methods: {
    async getListTransaction() {
      this.table.loading = true
      let param = {
        page: this.table.currentPage,
        search: this.table.search,
        perPage: this.table.perPage,
        order_direction: this.table.sortDesc,
        order_column: this.table.sortBy,
      }
      this.$store.dispatch("transaction/index", { params: param })
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
          this.getListTransaction()
        }
      }, 600)
    },
    getTransaction(id) {
      this.$store.dispatch("transaction/getTransaction", { id: id })
        .then((resp) => {
          this.transaction.detail = resp.data.detail
          this.transaction.sale = resp.data.sale
        })
        .catch((error) => {
        });
    },
    showTransaction(id) {
      this.getTransaction(id)
      this.$bvModal.show('modal-list-transaction')
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
      this.getListTransaction()
    },
    'table.perPage': function (newVal, oldVal) {
      if (this.table.currentPage == 1) {
        this.getListTransaction()
      } else {
        this.table.currentPage = 1
      }
    },
    'table.sortDesc': function (newVal, oldVal) {
      if (this.table.currentPage == 1) {
        this.getListTransaction()
      } else {
        this.table.currentPage = 1
      }
    },
    'table.sortBy': function (newVal, oldval) {
      if (this.table.currentPage == 1) {
        this.getListTransaction()
      } else {
        this.table.currentPage = 1
      }
    },
  },
  async created() {
    await this.getListTransaction();
  },
}
</script>
