<template>
    <div class="auth-wrapper d-flex align-center justify-center pa-4" :style="backgroundStyle">
      <VCard class="auth-card pa-4 pt-7" max-width="448">
        <VCardItem class="justify-center">
          <template #prepend>
            <div class="d-flex">
              <!-- <div class="d-flex text-primary w-32 h-16" v-html="logo" /> -->
              <div class="d-flex text-primary w-32 h-16" >
                <img src="@images/6s.png" alt="logo" style="width: 6.25rem; height: 6.25rem;"/>
              </div>
            </div>
          </template>
        </VCardItem>

        <VCardText class="pt-2">
          <h5 class="text-h5 mb-1">Welcome 👋🏻</h5>
          <p class="mb-0">Please sign-in to your account</p>
        </VCardText>

        <VCardText>
          <VForm @submit.prevent="login">
            <VRow>
              <!-- nik -->
              <VCol cols="12">
                <VTextField v-model="form.useremail" autofocus  label="Email / username" type="text" style="width: 23.125rem;" />
              </VCol>

              <!-- password -->
              <VCol cols="12">
                <VTextField v-model="form.password" label="Password" placeholder="············"
                  :type="isPasswordVisible ? 'text' : 'password'"
                  :append-inner-icon="isPasswordVisible ? 'bx-hide' : 'bx-show'"
                  @click:append-inner="isPasswordVisible = !isPasswordVisible" />

                <!-- remember me checkbox -->
                <div class="d-flex align-center justify-space-between flex-wrap mt-1 mb-4">
                  <!-- <VCheckbox v-model="form.remember" label="Remember me" /> -->

                  <!-- <RouterLink
                  class="text-primary ms-2 mb-1 mt-2"
                  to="javascript:void(0)"
                >
                  Forgot Password?
                </RouterLink> -->

                  <a href="#" class="text-primary ms-2 mb-1 mt-2" @click="forgotAlert">
                    Forgot Password?
                  </a>
                </div>

                <!-- login button -->
                <VBtn block type="submit"> Login </VBtn>
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </div>
  </template>

<style lang="scss">
@use "@core-scss/template/pages/page-auth.scss";
</style>
<script>
import mainURL from "@/axios";
import logo from "@images/6s.svg?raw";
import backgroundImage from '@images/lean-bg2.jpg';

export default {
  data() {
    return {
    backgroundStyle: {
        backgroundImage: `url(${backgroundImage})`,
        backgroundSize: 'cover',
        backgroundPosition: 'center',
        minHeight: '100vh'
      },
      userData: null,
      userToken: null,
      form: {
        useremail: null,
        password: "",
        remember: false,
      },
      isPasswordVisible: false,
      logo: logo,
    };
  },
  methods: {
    saveUserDataAndToken(data) {
      localStorage.setItem("userData", JSON.stringify(data.user));
      localStorage.setItem("userToken", data.token);
      localStorage.setItem("userPermission", data.permissions);
      localStorage.setItem("userRoles", data.roles);
    },

    forgotAlert() {
      alert('Silahkan Hubungi Administrator');
    },

    async login() {
      try {
        const response = await mainURL.post("/login", {
          identity: this.form.useremail,
          password: this.form.password,
        });

        if (response.status === 200) {
          this.saveUserDataAndToken(response.data);
          
          await this.$router.push("/dashboard");

          this.$showToast("success", "Selamat", "Anda berhasil login, mengarahkan ke dashboard.....");
        } else {
          const errorMessage =
            response && response.data && response.data.message
              ? response.data.message
              : "Gagal login. Silakan coba lagi.";
          this.$showToast("error", "Sorry", errorMessage);
        }
      } catch (error) {
        const errorMessage =
          error.response && error.response.data && error.response.data.message
            ? error.response.data.message
            : "Gagal login. Silakan coba lagi.";
        this.$showToast("error", "Sorry", errorMessage);
      }
    },
  },
};
</script>
