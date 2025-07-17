<script>
import mainURL from "@/axios";

export default {
  name: "InstaLayout",
  data() {
    return {
      user: null,
      drawer: true,
      rightDrawer: true,
      sugestedUser: null,
      postDialog: false,
      newPost: {
        caption: "",
        image: null,
      },
    };
  },
  methods: {
    go(path) {
      this.$router.push(path);
    },
    loadUser() {
      const storedUser = localStorage.getItem("userData");
      const sugestedUser = localStorage.getItem("sugestedUser");
      if (storedUser) {
        this.user = JSON.parse(storedUser);
        this.sugestedUser = JSON.parse(sugestedUser);
      }
    },
    addPost() {
      this.postDialog = true;
    },
    handleImage(event) {
      const file = event.target.files[0];
      if (file) {
        this.newPost.image = file;
        const reader = new FileReader();
        reader.onload = (e) => {
          this.newPost.imagePreview = e.target.result;
        };
        reader.readAsDataURL(file);
      }
    },

    async submitPost() {
      if (!this.newPost.caption || !this.newPost.image) {
        alert("Please provide both image and caption.");
        return;
      }

      const formData = new FormData();
      formData.append("caption", this.newPost.caption);
      formData.append("image", this.newPost.image);

      try {
        const response = await mainURL.post("/post/create", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        });
        this.postDialog = false;
        this.newPost = {
          caption: "",
          image: null,
          imagePreview: null,
        };

        // Jika kamu ingin reset input file-nya juga
        if (this.$refs.imageInput) {
          this.$refs.imageInput.value = null;
        }
        this.$showToast(
            "success",
            "Berhasil",
            "Post berhasil dibuat..."
          );
      } catch (error) {
        console.error("Gagal posting:", error.response?.data || error.message);
        alert(
          "Gagal mengirim post: " +
            (error.response?.data?.message || error.message)
        );
      }
    },
  },
  mounted() {
    this.loadUser();
  },
};
</script>

<template>
  <v-app class="bg-black">
    <v-app-bar flat class="bg-black text-white d-md-none" app>
      <v-btn icon @click="drawer = !drawer">
        <v-icon>mdi-menu</v-icon>
      </v-btn>
      <v-spacer />
      <span style="font-family: 'Billabong', cursive; font-size: 24px"
        >Instagram</span
      >
      <v-spacer />
      <v-btn icon @click="rightDrawer = !rightDrawer">
        <v-icon>mdi-account</v-icon>
      </v-btn>
    </v-app-bar>

    <v-navigation-drawer
      v-model="drawer"
      app
      class="bg-black text-white"
      width="250"
      :permanent="$vuetify.display.mdAndUp"
      :temporary="$vuetify.display.smAndDown"
    >
      <div class="px-4 py-4 d-flex align-center">
        <span style="font-family: 'Billabong', cursive; font-size: 28px"
          >Instagram</span
        >
      </div>

      <v-list nav dense class="text-white">
        <v-list-item
          @click="go('/dashboard')"
          prepend-icon="mdi-home"
          title="Home"
        />
        <v-list-item
          @click="go('/dashboard')"
          prepend-icon="mdi-magnify"
          title="Search"
        />
        <v-list-item
          @click="go('/dashboard')"
          prepend-icon="mdi-compass"
          title="Explore"
        />
        <v-list-item
          @click="go('/dashboard')"
          prepend-icon="mdi-movie-outline"
          title="Reels"
        />
        <v-list-item
          @click="go('/dashboard')"
          prepend-icon="mdi-message-outline"
          title="Messages"
        />
        <v-list-item
          @click="go('/dashboard')"
          prepend-icon="mdi-heart-outline"
          title="Notifications"
        />
        <v-list-item
          @click="addPost"
          prepend-icon="mdi-plus-outline"
          title="Create"
        />
      </v-list>
    </v-navigation-drawer>

    <v-navigation-drawer
      v-model="rightDrawer"
      app
      location="right"
      class="bg-black text-white"
      width="300"
      :permanent="$vuetify.display.mdAndUp"
      :temporary="$vuetify.display.smAndDown"
    >
      <div class="pa-4">
        <div class="d-flex align-center mb-4">
          <v-avatar size="48" class="mr-3">
            <img
              :src="
                'https://i.pravatar.cc/150?u=' + (user?.username || 'default')
              "
              alt="User Avatar"
            />
          </v-avatar>
          <div>
            <div class="font-weight-bold">{{ user?.username }}</div>
            <div class="text-caption text-grey">{{ user?.full_name }}</div>
          </div>
        </div>

        <v-divider class="my-4" color="grey-darken-1" />

        <div class="text-subtitle-2 mb-3 text-white">Suggested for you</div>
        <v-list nav dense class="text-white">
          <v-list-item
            class="px-0 mb-2"
            v-for="suges in sugestedUser"
            :key="suges?.id"
          >
            <template #prepend>
              <v-avatar size="32">
                <img
                  :src="
                    'https://i.pravatar.cc/150?u=' + (user?.email || 'default')
                  "
                  alt="{{ suges?.username }}"
                />
              </v-avatar>
            </template>
            <v-list-item-title class="text-white">{{
              suges?.username
            }}</v-list-item-title>
            <v-list-item-subtitle class="text-caption text-white"
              >Suggested for you</v-list-item-subtitle
            >
            <template #append>
              <v-btn size="small" variant="text" color="primary">Follow</v-btn>
            </template>
          </v-list-item>
        </v-list>
      </div>
    </v-navigation-drawer>

    <v-main class="bg-black">
      <v-container class="pa-0 h-100">
        <div class="d-flex justify-center">
          <div
            class="main-content-wrapper"
            :class="{
              'with-right-drawer': $vuetify.display.mdAndUp,
              'without-right-drawer': !$vuetify.display.mdAndUp,
            }"
          >
            <slot />
          </div>
        </div>
      </v-container>
      <v-dialog v-model="postDialog" max-width="900px" persistent>
        <v-card class="bg-black text-white">
          <v-card-title class="d-flex align-center">
            <v-btn variant="text" @click="postDialog = false" class="mr-2">
              <v-icon size="20">mdi-arrow-left</v-icon>
            </v-btn>
            <span class="text-h6 font-weight-bold">Create new post</span>
            <v-spacer></v-spacer>
            <v-btn
              variant="text"
              class="text-blue-lighten-2 font-weight-bold"
              @click="submitPost"
              >Share</v-btn
            >
          </v-card-title>

          <v-divider></v-divider>

          <v-card-text class="pa-0">
            <v-row no-gutters>
              <v-col
                cols="7"
                class="d-flex align-center justify-center bg-grey-darken-4"
              >
                <input
                  ref="imageInput"
                  type="file"
                  accept="image/*"
                  class="d-none"
                  @change="handleImage"
                />

                <div
                  v-if="newPost.imagePreview"
                  @click="$refs.imageInput.click()"
                  class="w-100 h-100 d-flex align-center justify-center"
                >
                  <v-img
                    :src="newPost.imagePreview"
                    contain
                    max-height="100%"
                  ></v-img>
                </div>
                <div
                  v-else
                  @click="$refs.imageInput.click()"
                  class="text-center text-grey"
                  style="cursor: pointer"
                >
                  <v-icon size="64">mdi-image-outline</v-icon>
                  <div class="mt-2">Upload Image</div>
                </div>
              </v-col>
              <v-col cols="5" class="bg-black pa-4">
                <div class="d-flex align-center mb-4">
                  <v-avatar size="40" class="mr-3">
                    <v-img
                      :src="
                        'https://i.pravatar.cc/150?u=' +
                        (user?.email || 'default')
                      "
                    ></v-img>
                  </v-avatar>
                  <span class="font-weight-bold text-white text-h6"
                    >yogabayu.ap</span
                  >
                </div>

                <v-textarea
                  label="Caption"
                  variant="plain"
                  v-model="newPost.caption"
                  rows="6"
                  no-resize
                  counter
                  maxlength="2200"
                  hide-details
                  class="caption-textarea"
                ></v-textarea>
                <div class="text-right text-grey-lighten-1 text-caption mt-1">
                  {{ newPost.caption ? newPost.caption.length : 0 }}/2,200
                </div>

                <v-divider class="my-4"></v-divider>

                <v-list dense class="bg-black">
                  <v-list-item class="px-0">
                    <v-list-item-title class="text-white"
                      >Add location</v-list-item-title
                    >
                    <template v-slot:append>
                      <v-icon>mdi-map-marker-outline</v-icon>
                    </template>
                  </v-list-item>
                  <v-list-item class="px-0">
                    <v-list-item-title class="text-white"
                      >Add collaborators</v-list-item-title
                    >
                    <template v-slot:append>
                      <v-icon>mdi-account-multiple-plus-outline</v-icon>
                    </template>
                  </v-list-item>
                </v-list>
              </v-col>
            </v-row>
          </v-card-text>
        </v-card>
      </v-dialog>
    </v-main>
  </v-app>
</template>

<style scoped>
.v-list-item-title {
  font-weight: 500;
}

.main-content-wrapper {
  width: 100%;
  /* max-width: 600px; */
  padding: 16px;
  min-height: 100vh;
}

.without-right-drawer {
  margin-right: 0;
}

/* Ensure proper spacing on different screen sizes */
@media (max-width: 768px) {
  .main-content-wrapper {
    max-width: 100%;
    margin-right: 0 !important;
  }
}

/* Custom scrollbar for dark theme */
.v-navigation-drawer ::-webkit-scrollbar {
  width: 6px;
}

.v-navigation-drawer ::-webkit-scrollbar-track {
  background: #1a1a1a;
}

.v-navigation-drawer ::-webkit-scrollbar-thumb {
  background: #444;
  border-radius: 3px;
}

.v-navigation-drawer ::-webkit-scrollbar-thumb:hover {
  background: #555;
}
</style>
