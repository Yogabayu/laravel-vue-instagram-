<template>
  <div class="login-wrapper d-flex align-center justify-center">
    <VRow class="fill-height w-100 pa-2" align="center" justify="center">
      <VCol cols="12" sm="8" md="4">
        <VCard class="login-card text-center pa-6">
          <div class="login-logo logo-text">Instagram</div>

          <VForm @submit.prevent="login" class="mt-4">
            <VTextField
              v-model="form.useremail"
              label="Username, or email"
              dense
              hide-details
              outlined
              color="grey-lighten-2"
              class="mb-3"
              :style="{ color: '#fff' }"
            />

            <VTextField
              v-model="form.password"
              label="Password"
              :type="isPasswordVisible ? 'text' : 'password'"
              :append-inner-icon="isPasswordVisible ? 'mdi-eye-off' : 'mdi-eye'"
              @click:append-inner="isPasswordVisible = !isPasswordVisible"
              dense
              hide-details
              outlined
              color="grey-lighten-2"
              class="mb-4"
            />

            <VBtn
              block
              type="submit"
              color="#4A5DF9"
              class="text-white font-weight-bold mb-2"
            >
              Log in
            </VBtn>
          </VForm>

          <div class="my-4 d-flex align-center">
            <div class="divider flex-grow-1"></div>
            <span class="mx-3 text-grey text-caption">OR</span>
            <div class="divider flex-grow-1"></div>
          </div>

          <div
            class="d-flex flex-row align-center justify-center text-facebook mb-3"
          >
            <v-icon class="me-1">mdi-facebook</v-icon>
            Log in with Facebook
          </div>

          <a href="#" class="text-caption text-white" @click="forgotAlert"
            >Forgot password?</a
          >
        </VCard>

        <VCard class="mt-4 text-center pa-4 login-card">
          <span class="text-caption">Don't have an account?</span>
          <RouterLink
            to="/register"
            class="text-primary font-weight-bold ms-1 text-caption"
            >Sign up</RouterLink
          >
        </VCard>
        <div class="d-flex flex-row align-center justify-center mt-4 text-caption text-white">
          <span>Get the App.</span>
          <div>
            <v-icon class="mx-1">mdi-apple</v-icon>
            <v-icon class="mx-1">mdi-android</v-icon>
          </div>
        </div>
      </VCol>
    </VRow>
  </div>
</template>

<script>
import mainURL from "@/axios";

export default {
  data() {
    return {
      form: {
        useremail: "",
        password: "",
      },
      isPasswordVisible: false,
    };
  },
  mounted() {
    const link = document.createElement('link')
    link.href = 'https://fonts.cdnfonts.com/css/billabong'
    link.rel = 'stylesheet'
    link.id = 'billabong-font'
    document.head.appendChild(link)
  },
  beforeUnmount() {
    const fontLink = document.getElementById('billabong-font')
    if (fontLink) document.head.removeChild(fontLink)
  },
  methods: {
    forgotAlert() {
      alert("username : ifisher, password : password");
    },
    async login() {
      try {
        const response = await mainURL.post("/login", {
          identity: this.form.useremail,
          password: this.form.password,
        });

        if (response.status === 200) {
          localStorage.setItem("userData", JSON.stringify(response.data.user));
          localStorage.setItem("userToken", response.data.token);
          localStorage.setItem("sugestedUser", JSON.stringify(response.data.sugestedUser));
          this.$router.push("/dashboard");
          this.$showToast(
            "success",
            "Berhasil",
            "Login berhasil. Mengarahkan ke dashboard..."
          );
        }
      } catch (error) {
        const msg = error.response?.data?.message || "Login gagal.";
        this.$showToast("error", "Oops", msg);
      }
    },
  },
};
</script>

<style scoped>
.logo-text {
  font-family: 'Billabong', sans-serif;
  font-size: 48px;
}
.login-wrapper {
  background-color: #000;
  min-height: 100vh;
  padding: 2rem;
}

.login-card {
  background-color: #000000;
  border: 1px solid #262626;
  box-shadow: none;
}

.login-logo {
  font-family: "Billabong", cursive;
  font-size: 3rem;
  color: white;
}

.divider {
  height: 1px;
  background-color: #000000;
}
.text-facebook{
  color: #3b5998;
}
</style>

<!-- Load Billabong font -->
<!-- Add this to your public/index.html -->
<!-- 
<link href="https://fonts.cdnfonts.com/css/billabong" rel="stylesheet" />
-->
