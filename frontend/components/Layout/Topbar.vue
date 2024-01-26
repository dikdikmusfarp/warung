<template>
  <!-- Topbar -->
  <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
    <!-- Topbar Search -->
    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search" v-if="role==2">
      <div class="input-group">
        <button v-on:click="cart()" class="btn btn-primary" type="button">
          <i class="fas fa-shopping-cart fa-sm"></i>
        </button>
      </div>
    </form>


    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

      <div class="topbar-divider d-none d-sm-block"></div>

      <!-- Nav Item - User Information -->
      <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true"
          aria-expanded="false" v-on:click="logout()">
          <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ user.name }}</span>
          <img class="img-profile rounded-circle" src="https://bootdey.com/img/Content/avatar/avatar7.png">
        </a>
      </li>

    </ul>
    <button class="btn btn-danger" type="button" v-on:click="logout()">
      <i class="fas fa-sign-out-alt fa-sm"></i>
    </button>

    <b-modal id="modal-list-cart" centered size="xl" title="List product on cart" title-class="font-27" hide-footer
      no-close-on-backdrop>
      <div class="row">
        <div class="col-12">
          <table class="table table-bordered borderBlack mb-2">
            <thead>
              <!-- <tr class="bg-primary">
                <th colspan="5" class="text-white">DIKDIK MUSFAR</th>
              </tr> -->
              <tr class="softGreen">
                <th width="5%" scope="col">No</th>
                <th width="40%" scope="col">Product Name</th>
                <th width="15%" scope="col">Price</th>
                <th width="10%" scope="col">Amount</th>
                <th width="15%" scope="col">Total</th>
                <th width="15%" scope="col">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, idx) in carts.data" :key="idx" v-if="(carts.data).length > 0">
                <th scope="row">{{ idx + 1 }}</th>
                <td>{{ item.name }}</td>
                <td>Rp. {{ item.price }}</td>
                <td>
                  {{ item.amount }}
                </td>
                <td>Rp. {{ item.total }}</td>
                <td>
                  <b-button v-on:click="deleteProduct(item.product_id, item.name)" size="sm" variant="danger">
                    <i class="fas fa-trash"></i>
                  </b-button>
                </td>
              </tr>
              <tr class="bg-primary">
                <th colspan="4" class="text-white text-center">Grand Total</th>
                <th class="text-white">Rp. {{ carts.grandTotal }}</th>
                <th>
                  <b-button v-on:click="checkOut()" size="sm" variant="success">
                    Check out
                  </b-button>
                </th>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </b-modal>

  </nav>
  <!-- End of Topbar -->
</template>

<script>
import Swal from 'sweetalert2'

export default {
  data() {
    return {
      carts: {
        data: [],
        grandTotal: 0,
      },
      role: null,
    }
  },
  methods: {
    logout() {
      Swal.fire({
        title: "Sign Out?",
        text: "Are you sure you want to sign out of the account?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#16A75C",
        cancelButtonColor: "#E53935",
        confirmButtonText: "Yes",
        cancelButtonText: "Cancel"
      }).then(result => {
        if (result.value) {
          this.$store
            .dispatch("auth/logout", {})
            .then((resp) => {
              Swal.fire("Success!", "See you again!", "success")
                .then((result) => {
                  this.$router.push('/login')
                });
            })
            .catch((error) => {
              Swal.fire("Error!", "failed sign out", "danger");
            });
        }
      });
    },
    cart() {
      this.getListCart()
      this.$bvModal.show('modal-list-cart')
    },
    getListCart() {
      this.$store.dispatch("cart/getListCart", {})
        .then((resp) => {
          this.carts.data = resp.data.data
          this.carts.grandTotal = resp.data.grandTotal
        })
        .catch((error) => {
        });
    },
    deleteProduct(id, name) {
      Swal.fire({
        title: "Remove from cart?",
        text: "Are you sure you want to delete" + name + "from your cart?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#16A75C",
        cancelButtonColor: "#E53935",
        confirmButtonText: "Yes",
        cancelButtonText: "Cancel"
      }).then(result => {
        if (result.value) {
          this.$store.dispatch("cart/deleteProduct", { id: id })
            .then((resp) => {
              Swal.fire("Success!", "Product has been deleted!", "success")
                .then((result) => {
                  this.getListCart();
                });
            })
            .catch((error) => {
              Swal.fire("Error!", "Failed to delete product from your cart", "danger");
            });
        }
      });
    },
    updateProduct(id) {
      this.getProduct(id)
      this.$bvModal.show('modal-update-form')
    },
    checkOut() {
      Swal.fire({
        title: "Check out?",
        text: "Are you sure you want to check out all products on your cart?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#16A75C",
        cancelButtonColor: "#E53935",
        confirmButtonText: "Yes",
        cancelButtonText: "Cancel"
      }).then(result => {
        if (result.value) {
          this.$store
            .dispatch("transaction/store", {})
            .then((resp) => {
              Swal.fire("Success!", "Thank you!", "success")
                .then((result) => {
                  this.$bvModal.hide('modal-list-cart')
                  this.carts.data = []
                  this.carts.grandTotal = 0
                });
            })
            .catch((error) => {
              Swal.fire("Error!", "Failed", "danger");
            });
        }
      });
    },
  },
  async created() {
    this.user = this.$store.state.auth.user
    if (this.user) {
      this.role = this.user.role_id
    }
  },
}

</script>
