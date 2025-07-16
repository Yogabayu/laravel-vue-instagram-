<script setup>
import VerticalNavSectionTitle from "@/@layouts/components/VerticalNavSectionTitle.vue";
import VerticalNavDropdown from "@layouts/components/VerticalNavDropdown.vue";
import VerticalNavLayout from '@layouts/components/VerticalNavLayout.vue';
import VerticalNavLink from '@layouts/components/VerticalNavLink.vue';
import { useTheme } from 'vuetify';

// Components
import NavbarThemeSwitcher from '@/layouts/user/components/NavbarThemeSwitcher.vue';
import UserProfile from '@/layouts/user/components/UserProfile.vue';

import mainURL from "@/axios";
import { onBeforeMount, ref } from "vue";

const vuetifyTheme = useTheme()
const isPhone = ref(false) // Initialize a ref for phone detection
if (typeof window !== 'undefined') {
  isPhone.value = window.innerWidth <= 600 // Adjust the width threshold according to your preference
}

const userData = JSON.parse(localStorage.getItem("userData"));

const categories = ref([]);

// Function to fetch categories
const getCategories = async () => {
  try {
    const response = await mainURL.get("/user/category");
    if (response.status === 200) {
      categories.value = response.data.data;
    } else {
      console.error("Failed to retrieve data");
    }
  } catch (error) {
    console.error("Error fetching categories:", error);
  }
};

// Call getCategories on component mount
onBeforeMount(() => {
  getCategories();
});
</script>

<template>
  <VerticalNavLayout>
    <!-- 👉 navbar -->
    <template #navbar="{ toggleVerticalOverlayNavActive }">
      <div class="d-flex h-100 align-center">
        <!-- 👉 Vertical nav toggle in overlay mode -->
        <!-- <IconBtn v-if="!isPhone" class="ms-n3 d-lg-none" @click="toggleVerticalOverlayNavActive(true)"> -->
        <IconBtn  class="ms-n3 d-lg-none" @click="toggleVerticalOverlayNavActive(true)">
          <VIcon icon="bx-menu"/>
        </IconBtn>

        <VSpacer />

        <NavbarThemeSwitcher class="me-2" />

        <UserProfile />
      </div>
    </template>

    <!-- <template #vertical-nav-content v-if="!isPhone"> -->
    <template #vertical-nav-content>
      <VerticalNavLink :item="{
        title: 'Dashboard',
        icon: 'bx-home',
        to: '/user-dashboard',
      }" />
      
      <VerticalNavLink :item="{
        title: 'Cari File',
        icon: 'bx-file-find',
        to:'/u-search'
      }" />
      <!-- Dropdown for SOP -->
      <div>
        <template v-for="(category, index) in categories" :key="index">
          <template v-if="category.sub_categories && category.sub_categories.length > 0">
            <VerticalNavDropdown :title="category.name" :icon="'bx-folder-open'" :key="index">
              <!-- Langsung loop sub_categories tanpa nested div -->
              <VerticalNavLink v-for="subcategory in category.sub_categories" :key="subcategory.id" :item="{
                to: `/u-search/${subcategory.id}`,
                title: subcategory.name,
                icon: 'bx-file',
              }" />
            </VerticalNavDropdown>
          </template>
          <template v-else>
            <VerticalNavLink :item="{
              to: `/u-subcategory/${category.id}`,
              title: category.name,
              icon: 'bx-folder-open',
            }" />
          </template>
        </template>
      </div>

      <VerticalNavLink :item="{
        title: 'Favorite',
        icon: 'bx-heart',
        to: '/u-favorite'
      }" />

      <VerticalNavLink :item="{
        title: 'Riwayat',
        icon: 'bx-show',
        to: '/u-read'
      }" />
      
      <VerticalNavSectionTitle v-if="userData.position.approval_level_id>1"
        :item="{
          heading: 'Pengajuan Draft',
        }"
      />
      <VerticalNavLink v-if="userData.position.approval_level_id>1" :item="{
        title: 'Draft',
        icon: 'bx-file',
        to:'/u-draft'
      }" />


    </template>


    <!-- 👉 Pages -->
    <slot />

    <!-- 👉 Footer -->
    <!-- <template #footer>
      <v-bottom-navigation :elevation="0" mode="shift" v-if="isPhone">
        <v-btn value="home" :to="'/user-dashboard'">
          <v-icon>mdi-home</v-icon>

          <span>Home</span>
        </v-btn>

        <v-btn value="search" :to="'/u-search'">
          <v-icon>mdi-text-box-search</v-icon>

          <span>Cari File</span>
        </v-btn>

        <v-btn value="favorite" :to="'/u-favorite'">
          <v-icon>mdi-heart</v-icon>

          <span>Favorite</span>
        </v-btn>

        <v-btn value="nearby" :to="'/u-read'">
          <v-icon>mdi-eye-outline</v-icon>

          <span>Riwayat</span>
        </v-btn>

        <v-btn value="draft" :to="'/u-draft'" v-if="userData.position.approval_level_id>1">
          <v-icon>mdi-file-clock</v-icon>

          <span>Draft</span>
        </v-btn>

      </v-bottom-navigation>
    </template> -->
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
