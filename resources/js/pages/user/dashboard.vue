<template>
  <div class="dashboard-container">
    <!-- Stories Section -->
    <div class="stories-section mb-6">
      <div class="stories-scroll">
        <div class="story-item" v-for="story in stories" :key="story.id">
          <div class="story-avatar-wrapper">
            <v-avatar size="66" :class="{ 'story-gradient': story.hasStory }">
              <img :src="story.avatar" :alt="story.username" />
            </v-avatar>
          </div>
          <span class="story-username">{{ story.username }}</span>
        </div>
      </div>
    </div>

    <!-- Feed Posts -->
    <div class="feed-section">
      <div class="post-card" v-for="post in posts" :key="post.id">
        <!-- Post Header -->
        <div class="post-header">
          <div class="d-flex align-center">
            <v-avatar size="32" class="story-gradient mr-3">
              <img
                :src="
                  'https://i.pravatar.cc/150?u=' +
                  (post.user?.email || 'default')
                "
                :alt="post.user?.username"
              />
            </v-avatar>
            <div>
              <div class="post-username">{{ post.user?.username }}</div>
              <div class="post-time">{{ post.created_at?.slice(0, 10) }}</div>
            </div>
          </div>
          <v-btn icon size="small" variant="text">
            <v-icon>mdi-dots-horizontal</v-icon>
          </v-btn>
        </div>

        <!-- Post Image -->
        <div class="post-image-container">
          <img
            v-if="post.image_path && post.image_path.startsWith('posts/')"
            :src="basePhoto + post.image_path"
            :alt="post.caption"
            class="post-image"
          />

          <img
            v-else-if="post.image_path && post.image_path.startsWith('images/')"
            :src="basePhoto + post.image_path"
            :alt="post.caption"
            class="post-image"
          />

          <img
            v-else
            :src="
              'https://i.pravatar.cc/150?u=' + (post.image_path || 'default')
            "
            :alt="post.caption"
            class="post-image"
          />

          <!-- <div class="post-overlay-text" v-if="post.overlayText">
            {{ post.overlayText }}
          </div> -->

          <div class="carousel-nav">
            <v-btn icon size="small" class="nav-btn nav-prev" variant="text">
              <v-icon size="20">mdi-chevron-left</v-icon>
            </v-btn>
          </div>
        </div>

        <!-- Post Actions -->
        <div class="post-actions">
          <div class="d-flex align-center">
            <v-btn
              icon
              size="small"
              variant="text"
              class="mr-2"
              @click="likesPost(post)"
            >
              <v-icon>mdi-heart-outline</v-icon>
            </v-btn>
            <v-btn icon size="small" variant="text" class="mr-2">
              <v-icon>mdi-comment-outline</v-icon>
            </v-btn>
            <v-btn icon size="small" variant="text" class="mr-2">
              <v-icon>mdi-send-outline</v-icon>
            </v-btn>
          </div>
          <v-btn icon size="small" variant="text">
            <v-icon>mdi-bookmark-outline</v-icon>
          </v-btn>
        </div>

        <!-- Post Stats -->
        <div class="post-stats">
          <div class="likes-count">{{ post.likes.length }} likes</div>
          <div class="post-caption">
            <span class="username">{{ post.username }}</span>
            {{ post.caption }}
          </div>
          <div class="view-comments" v-if="post.comments > 0">
            View all {{ post.comments }} comments
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import mainURL from "@/axios";

export default {
  name: "InstagramDashboard",
  data() {
    return {
      stories: [
        {
          id: 1,
          username: "desta80s",
          avatar: "https://i.pravatar.cc/150?u=desta80s",
          hasStory: true,
        },
        {
          id: 2,
          username: "futnhj",
          avatar: "https://i.pravatar.cc/150?u=futnhj",
          hasStory: true,
        },
        {
          id: 3,
          username: "nikendwi.nd",
          avatar: "https://i.pravatar.cc/150?u=nikendwi",
          hasStory: true,
        },
        {
          id: 4,
          username: "kukuhwah...",
          avatar: "https://i.pravatar.cc/150?u=kukuhwah",
          hasStory: false,
        },
        {
          id: 5,
          username: "n.rdn_",
          avatar: "https://i.pravatar.cc/150?u=nrdn",
          hasStory: true,
        },
        {
          id: 6,
          username: "dzawin_nur",
          avatar: "https://i.pravatar.cc/150?u=dzawin",
          hasStory: true,
        },
        {
          id: 7,
          username: "stockbit",
          avatar: "https://i.pravatar.cc/150?u=stockbit",
          hasStory: true,
        },
        {
          id: 8,
          username: "dewakoding",
          avatar: "https://i.pravatar.cc/150?u=dewakoding",
          hasStory: true,
        },
      ],
      posts: [
        {
          id: 1,
          username: "jelsinatosa_",
          userAvatar: "https://i.pravatar.cc/150?u=jelsinatosa",
          image:
            "https://images.unsplash.com/photo-1441974231531-c6227db76b6e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80",
          overlayText: "Salam kenal dari\nPetani muda Nganjuk 👋",
          caption: "Farming life in Nganjuk 🌾",
          likes: 1247,
          comments: 35,
          timeAgo: "8h",
        },
      ],
      basePhoto: this.$basephoto,
    };
  },
  methods: {
    async getPosts() {
      try {
        const response = await mainURL.get("/post/random");
        if (response.status === 200) {
          this.posts = response.data.data;
        }
      } catch (error) {
        console.error("Error fetching posts:", error);
      }
    },

    async likesPost(post) {
      try {
        const response = await mainURL.post(`/post/${post.id}/like`);

        const currentUser = JSON.parse(localStorage.getItem("userData"));

        const index = post.likes.findIndex(
          (like) => like.user_id === currentUser.id
        );

        if (index !== -1) {
          post.likes.splice(index, 1);
        } else {
          post.likes.push({ user_id: currentUser.id });
        }

        this.$showToast("success", "Success", response.data.message);
      } catch (error) {
        this.$showToast(
          "warning",
          "Warning",
          error.response?.data?.message || "Gagal memproses permintaan"
        );
      }
    },
  },
  mounted() {
    this.getPosts();
  },
};
</script>

<style scoped>
.dashboard-container {
  /* max-width: 470px; */
  margin: 0 auto;
  padding: 0 16px;
}

/* Stories Section */
.stories-section {
  padding: 16px 0;
  border-bottom: 1px solid #262626;
}

.stories-scroll {
  display: flex;
  gap: 16px;
  overflow-x: auto;
  scrollbar-width: none;
  -ms-overflow-style: none;
}

.stories-scroll::-webkit-scrollbar {
  display: none;
}

.story-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  min-width: 66px;
}

.story-avatar-wrapper {
  position: relative;
  margin-bottom: 8px;
}

.story-gradient {
  border: 2px solid transparent;
  background: linear-gradient(
    45deg,
    #f09433 0%,
    #e6683c 25%,
    #dc2743 50%,
    #cc2366 75%,
    #bc1888 100%
  );
  background-clip: padding-box;
  padding: 2px;
}

.story-gradient img {
  border-radius: 50%;
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.story-username {
  font-size: 12px;
  color: #fafafa;
  text-align: center;
  max-width: 66px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

/* Feed Posts */
.feed-section {
  padding-top: 16px;
}

.post-card {
  margin-bottom: 24px;
  background: transparent;
}

.post-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 12px 0;
}

.post-username {
  font-weight: 600;
  color: #fafafa;
  font-size: 14px;
}

.post-time {
  font-size: 12px;
  color: #8e8e8e;
}

.post-image-container {
  position: relative;
  border-radius: 8px;
  overflow: hidden;
  margin-bottom: 12px;
}

.post-image {
  width: 100%;
  height: 585px;
  object-fit: cover;
  display: block;
}

.post-overlay-text {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  color: white;
  font-size: 28px;
  font-weight: bold;
  text-align: center;
  text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
  white-space: pre-line;
  z-index: 2;
}

.carousel-nav {
  position: absolute;
  top: 50%;
  left: 12px;
  transform: translateY(-50%);
}

.nav-btn {
  background: rgba(0, 0, 0, 0.5) !important;
  color: white !important;
  backdrop-filter: blur(10px);
}

.nav-prev {
  opacity: 0.7;
}

.post-actions {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 8px 0;
}

.post-actions .v-btn {
  color: #fafafa;
}

.post-stats {
  color: #fafafa;
}

.likes-count {
  font-weight: 600;
  margin-bottom: 8px;
  font-size: 14px;
}

.post-caption {
  font-size: 14px;
  margin-bottom: 4px;
}

.post-caption .username {
  font-weight: 600;
  margin-right: 8px;
}

.view-comments {
  color: #8e8e8e;
  font-size: 14px;
  cursor: pointer;
}

.view-comments:hover {
  color: #fafafa;
}

/* Responsive */
@media (max-width: 768px) {
  .dashboard-container {
    max-width: 100%;
    padding: 0 8px;
  }

  .stories-section {
    padding: 8px 0;
  }

  .story-item {
    min-width: 56px;
  }

  .post-image {
    height: 400px;
  }

  .post-overlay-text {
    font-size: 22px;
  }
}
</style>
