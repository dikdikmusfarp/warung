<!-- Please remove this file from your project -->
<template>
  <body id="page-top">

<!-- Page Wrapper -->
<div id="wrapper">
    <sidebar></sidebar>
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <topbar></topbar>

            <!-- Begin Page Content -->
            <div class="container-fluid">

                <!-- Page Heading -->
                <nuxt />

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Made by &copy; <a href="https://www.linkedin.com/in/dikdikmusfar/" target="_blank" rel="noopener noreferrer">Dikdik Musfar</a></span>
                </div>
            </div>
        </footer>
        <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

</body>
</template>

<script>
import Sidebar from '~/components/Layout/Sidebar'
import Topbar from '~/components/Layout/Topbar'


export default {
  name: 'NuxtTutorial',
  components: {
    Sidebar,
    Topbar,
  },
}
</script>
