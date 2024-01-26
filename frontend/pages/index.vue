<template>
  <div>
    <p>Hello!</p>
  </div>
</template>

<script>
export default {
  name: 'IndexPage',
  layout: 'base',
  middleware: 'authentication',
}
</script>
