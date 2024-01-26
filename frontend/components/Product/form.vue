<template>
  <div class="row">
    <div class="col-xl-12">
      <form @submit.prevent="submitForm" @reset="onReset" class="form-horizontal" role="form"
        enctype="multipart/form-data">
        <div class="row">
          <div class="col-xl-6 mt-2">
            <label class="required">Product Name</label> :
            <div>
              <input id="rounded" autocomplete="off" v-model="form.name" type="text" class="form-control" name="name"
                :class="{ 'is-invalid': $v.form.name.$error }" placeholder="Enter the product name" />
              <div v-if="$v.form.name.$error" class="invalid-feedback">
                <span v-if="!$v.form.name.required">The product name must be filled in.</span>
              </div>
            </div>
          </div>
          <div class="col-xl-6 mt-2">
            <label class="required">Price</label> :
            <div>
              <input id="rounded" autocomplete="off" v-model="form.price" type="number" class="form-control" name="price"
                :class="{ 'is-invalid': $v.form.price.$error }" placeholder="Enter the product price" />
              <div v-if="$v.form.price.$error" class="invalid-feedback">
                <span v-if="!$v.form.price.required">The price must be filled in</span>
              </div>
            </div>
          </div>
          <div class="col-xl-6 mt-2">
            <label class="required">Stock</label> :
            <div>
              <input id="rounded" autocomplete="off" v-model="form.stock" type="number" class="form-control" name="stock"
                :class="{ 'is-invalid': $v.form.stock.$error }" placeholder="Enter product stock quantity" />
              <div v-if="$v.form.stock.$error" class="invalid-feedback">
                <span v-if="!$v.form.stock.required">The stock must be filled in</span>
              </div>
            </div>
          </div>
          <div class="col-xl-6 mt-2 form-group">
            <label for="exampleFormControlTextarea1">Description</label>
            <textarea v-model="form.description" class="form-control" id="exampleFormControlTextarea1" rows="3" :class="{ 'is-invalid': $v.form.description.$error }" placeholder="Enter product description"></textarea>
            <div v-if="$v.form.description.$error" class="invalid-feedback">
                <span v-if="!$v.form.description.required">The description must be filled in</span>
              </div>
          </div>
        </div>
        <div class="row text-center pt-4 pb-4">
          <b-button variant="primary" class="w-md" type="submit"><i class="uil uil-save mx-1"></i>Simpan</b-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { required } from "vuelidate/lib/validators";

export default {
  props: {
    items: {
      type: Object,
      default: () => {
        return {}
      },
    },
  },
  data() {
    return {
      form: {
        name: this.items.name,
        price: this.items.price,
        description: this.items.description,
        stock: this.items.stock,
      },
    };
  },
  validations: {
    form: {
      name: {
        required
      },
      price: {
        required
      },
      description: {
        required
      },
      stock: {
        required
      },
    }
  },
  watch: {
    items: {
      handler: function (val) {
        if (val) {
          this.form.name = val.name
          this.form.price = val.price
          this.form.description = val.description
          this.form.stock = val.stock
        }
      },
      deep: true,
      immediate: true,
    },
  },
  methods: {
    submitForm() {
      this.$v.$touch()
      if (!this.$v.$invalid) {
        this.submit = true;
        this.$emit('input', this.form)
      }
    },
    onReset(event) {
      event.preventDefault();
      // Reset our form values
      this.form.name = null
      this.form.price = null
      this.form.stock = null
      this.form.description = null
      this.$v.$reset();
    },
  },
  async created() {
  },
  mounted() {
  }
};
</script>
