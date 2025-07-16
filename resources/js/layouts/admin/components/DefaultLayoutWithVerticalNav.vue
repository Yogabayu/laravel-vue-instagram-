<script setup>
import VerticalNavSectionTitle from "@/@layouts/components/VerticalNavSectionTitle.vue";
import VerticalNavDropdown from "@layouts/components/VerticalNavDropdown.vue";
import VerticalNavLayout from "@layouts/components/VerticalNavLayout.vue";
import VerticalNavLink from "@layouts/components/VerticalNavLink.vue";
import { useTheme } from "vuetify";

// Components
import Footer from "@/layouts/admin/components/Footer.vue";
import NavbarThemeSwitcher from "@/layouts/admin/components/NavbarThemeSwitcher.vue";
import UserProfile from "@/layouts/admin/components/UserProfile.vue";

// Vuetify theme usage
const vuetifyTheme = useTheme();

const userPermission = localStorage.getItem("userPermission");
</script>

<template>
  <VerticalNavLayout>
    <!-- 👉 navbar -->
    <template #navbar="{ toggleVerticalOverlayNavActive }">
      <div class="d-flex h-100 align-center">
        <!-- 👉 Vertical nav toggle in overlay mode -->
        <IconBtn
          class="ms-n3 d-lg-none"
          @click="toggleVerticalOverlayNavActive(true)"
        >
          <VIcon icon="bx-menu" />
        </IconBtn>

        <VSpacer />

        <NavbarThemeSwitcher class="me-2" />

        <UserProfile />
      </div>
    </template>

    <template #vertical-nav-content>
      <VerticalNavLink
        :item="{
          title: 'Dashboard',
          icon: 'bx-home',
          to: '/dashboard',
        }"
      />
      <VerticalNavLink
        :item="{
          title: 'Findings',
          icon: 'bx-search',
          to: '/findings',
        }"
      />
      <VerticalNavDropdown title="6S Performance" icon="bx-chart">
        <!-- Add your dropdown items as slots -->
        <VerticalNavLink
          :item="{
            title: 'Overview',
            icon: 'bx-pie-chart',
            to: '/performance',
          }"
        />

        <VerticalNavLink
          :item="{
            title: 'Analytics',
            icon: 'bx-bar-chart',
            to: '/performance/analytics',
          }"
        />
      </VerticalNavDropdown>

      <!-- 👉 konfigurasi -->
      <VerticalNavSectionTitle
        :item="{
          heading: 'Konfigurasi',
        }"
      />
      <VerticalNavLink
        v-if="
          userPermission &&
          (userPermission.includes('create-user') ||
            userPermission.includes('update-user') ||
            userPermission.includes('view-user') ||
            userPermission.includes('delete-user'))
        "
        :item="{
          title: 'User',
          icon: 'bx-user',
          to: '/users',
        }"
      />
      <VerticalNavLink
        v-if="
          userPermission &&
          (userPermission.includes('create-area') ||
            userPermission.includes('update-area') ||
            userPermission.includes('view-area') ||
            userPermission.includes('delete-area'))
        "
        :item="{
          title: 'Area',
          icon: 'bx-area',
          to: '/areas',
        }"
      />
    </template>

    <!-- 👉 Pages -->
    <slot />

    <!-- 👉 Footer -->
    <template #footer>
      <Footer />
    </template>
  </VerticalNavLayout>
</template>

<style lang="scss" scoped>
.meta-key {
  border: thin solid rgba(var(--v-border-color), var(--v-border-opacity));
  border-radius: 6px;
  block-size: 1.5625rem;
  line-height: 1.3125rem;
  padding-block: 0.125rem;
  padding-inline: 0.25rem;
}
</style>
