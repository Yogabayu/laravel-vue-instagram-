<template>
  <div class="login-wrapper d-flex align-center justify-center">
    <VRow class="fill-height w-100 pa-2" align="center" justify="center">
      <VCol cols="12" sm="8" md="4">
        <VCard class="login-card text-center pa-6">
          <div class="login-logo logo-text">Instagram</div>
          <div class="text-grey-lighten-1 mb-4">Sign up to see photos and videos from your friends.</div>

          <div
            class="d-flex flex-row align-center justify-center text-facebook mb-4"
            style="cursor: pointer;"
          >
            <v-icon class="me-1">mdi-facebook</v-icon>
            Log in with Facebook
          </div>

          <div class="my-4 d-flex align-center">
            <div class="divider flex-grow-1"></div>
            <span class="mx-3 text-grey text-caption">OR</span>
            <div class="divider flex-grow-1"></div>
          </div>

          <VForm @submit.prevent="register" class="mt-4">
            <VTextField
              v-model="form.email"
              label="Email"
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
              class="mb-3"
            />

            <VTextField
              v-model="form.fullName"
              label="Full Name"
              dense
              hide-details
              outlined
              color="grey-lighten-2"
              class="mb-3"
              :style="{ color: '#fff' }"
            />

            <VTextField
              v-model="form.username"
              label="Username"
              dense
              hide-details
              outlined
              color="grey-lighten-2"
              class="mb-4"
              :style="{ color: '#fff' }"
            />

            <div class="text-caption text-grey-lighten-1 mb-4 text-center">
              People who use our service may have uploaded your contact information to Instagram. 
              <a href="#" class="text-white">Learn More</a>
            </div>

            <div class="text-caption text-grey-lighten-1 mb-4 text-center">
              By signing up, you agree to our 
              <a href="#" class="text-white">Terms</a>, 
              <a href="#" class="text-white">Privacy Policy</a> and 
              <a href="#" class="text-white">Cookies Policy</a>.
            </div>

            <VBtn
              block
              type="submit"
              color="#4A5DF9"
              class="text-white font-weight-bold mb-2"
            >
              Sign up
            </VBtn>
          </VForm>
        </VCard>

        <VCard class="mt-4 text-center pa-4 login-card">
          <span class="text-caption">Have an account?</span>
          <RouterLink
            to="/login"
            class="text-primary font-weight-bold ms-1 text-caption"
            >Log in</RouterLink
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
        email: "",
        password: "",
        fullName: "",
        username: "",
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
    async register() {
      try {
        const response = await mainURL.post("/register", {
          email: this.form.email,
          password: this.form.password,
          fullName: this.form.fullName,
          username: this.form.username,
        });

        if (response.status === 200) {
          this.$showToast(
            "success",
            "Berhasil",
            "Registrasi berhasil. Silakan login dengan akun baru Anda."
          );
          this.$router.push("/login");
        }
      } catch (error) {        
        const msg = error.message;
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
  background-color: #262626;
}
.text-facebook{
  color: #3b5998;
}
</style>
