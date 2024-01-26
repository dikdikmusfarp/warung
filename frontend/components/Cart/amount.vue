<template>
  <div class="row">
    <div class="col-xl-12">
      <form @submit.prevent="submitForm" @reset="onReset" class="form-horizontal" role="form"
        enctype="multipart/form-data">
        <div class="row">
          <div class="col-xl-6 mt-2">
            <label class="required">Product Name</label> :
            <div>
              <input id="rounded" autocomplete="off" v-model="form.name" type="text" class="form-control" name="name" placeholder="Enter the product name" />
            </div>
          </div>
          <div class="col-xl-6 mt-2">
            <label class="required">Price</label> :
            <div>
              <input id="rounded" autocomplete="off" v-model="form.price" type="number" class="form-control" name="price" placeholder="Enter the product price" />
            </div>
          </div>
          <div class="col-xl-6 mt-2">
            <label class="required">Stock</label> :
            <div>
              <input id="rounded" autocomplete="off" v-model="form.amount" type="number" class="form-control" name="amount"
                :class="{ 'is-invalid': $v.form.amount.$error }" placeholder="Enter product amount quantity" />
              <div v-if="$v.form.amount.$error" class="invalid-feedback">
                <span v-if="!$v.form.amount.required">The amount must be filled in</span>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-12 text-center pt-4 pb-4">
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
        amount: this.items.amount,
      },
    };
  },
  validations: {
    form: {
      amount: {
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
          this.form.amount = val.amount
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
      this.form.amount = 0
      this.$v.$reset();
    },
  },
  async created() {
  },
  mounted() {
  }
};
</script>
