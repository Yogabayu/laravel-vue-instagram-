<template>
  <div class="misc-wrapper">
    <ErrorHeader
      error-code="404"
      error-title="Unauthorized action ⚠️"
      error-description="Anda dilarang masuk"
    />

    <!-- 👉 Image -->
    <div class="misc-avatar w-100 text-center">
      <VImg
        :src="misc404"
        alt="Error"
        :max-width="800"
        class="mx-auto"
      />
    </div>
  </div>
</template>

<script setup>
import ErrorHeader from '@/components/ErrorHeader.vue';
import misc404 from '@images/pages/404.png';

</script>

<style lang="scss">
@use "@core-scss/template/pages/misc.scss";
</style>
