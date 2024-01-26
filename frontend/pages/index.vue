<template>
  <div>
    <customer></customer>
  </div>
</template>

<script>
import customer from '~/components/Dashboard/customer'

export default {
  name: 'IndexPage',
  layout: 'base',
  middleware: 'authentication',
  components: {
    customer
  },
}
</script>
