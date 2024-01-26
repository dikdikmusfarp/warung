<template>
  <div>
    <div class="row">
      <div class="col-xl-12">
        <div class="card">
          <div class="card-body">
            <div class="row mb-2">
              <div class="col-xl-2">
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
                    TAMPILKAN&nbsp;
                    <b-form-select id="rounded" v-model="table.perPage" size="sm" :options="table.pageOptions">
                    </b-form-select>
                    &nbsp;DATA
                  </label>
                </div>
              </div>
              <!-- Search -->
              <div class="col-sm-12 col-md-8">
                <div id="tickets-table_filter" class="dataTables_filter text-md-end">
                  <label class="d-inline-flex align-items-center">
                    CARI:
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
                  <template v-slot:cell(nomor)="{ index }">
                    {{ (index + 1 + number) }}
                  </template>
                  <template v-slot:cell(actions)="{ item }">
                    <!-- <b-button v-on:click="ajukanMutasi(item.id_sekolah, item.nama_sekolah, item.label_mata_pelajaran)"
                      variant="info" class="text-white" type="submit" size="sm" style="margin-top: 0.2rem;"><i
                        class="uil uil-arrow-up-right"></i> ajukan
                    </b-button> -->
                  </template>
                </b-table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <b-modal id="modal-create-form" centered size="lg" title="Make a new product" title-class="font-27"
        hide-footer no-close-on-backdrop>
        <div class="row">
          <div class="col-12">
            <product-form ref="revisi" :items="form" @input="submitCreate"></product-form>
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
        name: null,
        description: null,
        price: null,
        stock: null,
      }
    }
  },
  methods: {
    debounceSearch(val) {
      clearTimeout(this.debounce)
      this.debounce = setTimeout(() => {
        if (this.table.currentPage !== 1) {
          this.table.currentPage = 1
        } else {
          // this.listKebutuhan()
        }
      }, 600)
    },
    submitCreate(productForm) {
      let form = productForm;
      console.log(form);
      // this.$store
      //   .dispatch("item/store", { form })
      //   .then((resp) => {
      //     console.log(resp);
      //     this.getListItem();
      //     this.form.id = null;
      //     this.form.name = null;
      //     this.form.price = null;
      //     this.form.type_id = null;
      //     this.$bvModal.hide('modal-create-form')
      //   })
      //   .catch((error) => {
      //   });
    },
    newProduct() {
      this.$bvModal.show('modal-create-form')
    },
  }
}
</script>
