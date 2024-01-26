<template>
  <!-- Sidebar -->
  <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <nuxt-link class="sidebar-brand d-flex align-items-center justify-content-center" to="/">
      <div class="sidebar-brand-icon rotate-n-15">
        <i class="fas fa-laugh-wink"></i>
      </div>
      <div class="sidebar-brand-text mx-3">W A R U N G</div>
    </nuxt-link>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
      <nuxt-link to="/" class="nav-link">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Dashboard</span>
      </nuxt-link>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
      Transaction
    </div>

    <!-- Nav Item - Charts -->
    <li class="nav-item" v-if="role==1">
      <nuxt-link to="/product" class="nav-link">
        <i class="fas fa-fw fa-chart-area"></i>
        <span>Product</span>
      </nuxt-link>
    </li>

    <!-- Nav Item - Tables -->
    <li class="nav-item">
      <nuxt-link to="/transaction" class="nav-link">
        <i class="fas fa-fw fa-chart-area"></i>
        <span>Transaction</span>
      </nuxt-link>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">


  </ul>
  <!-- End of Sidebar -->
</template>

<script>
export default {
  data() {
    return {
      role: null,
    }
  },
  async created() {
    this.role = this.$store.state.auth.user.role_id
  },
}
</script>
