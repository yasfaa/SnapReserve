<template>
    <div class="profile-page d-flex">
        <Sidenav v-show="!isMobile" class="sidenav" />

        <div class="profile-content flex-grow-1">
            <div class="profile-header ms-3 py-6 d-flex row">
                <img
                    :src="user.path || 'http://www.gravatar.com/avatar/?d=mp'"
                    alt="Profile Picture"
                    class="profile-picture me-4 rounded-circle"
                />
                <div class="profile-info">
                    <div class="profile-header-top">
                        <h2>{{ user.name }}</h2>
                        <div class="d-flex mt-2">
                            <button
                                @click="goToEditProfile"
                                class="edit-profile-btn me-2"
                            >
                                Edit Profile
                            </button>
                            <button
                                @click="goToArchive"
                                class="view-archive-btn"
                            >
                                View Archive
                            </button>
                        </div>
                    </div>
                    <div class="profile-stats mt-3">
                        <span>{{ user.bio }} </span>
                    </div>
                </div>
            </div>

            <div
                v-if="postsCount > 0"
                class="profile-posts pt-5"
                :style="{ gridTemplateColumns: `repeat(${feedPerRow}, 1fr)` }"
            >
                <div
                    v-for="(image, index) in postImages"
                    :key="image.id"
                    class="post-image"
                    @click="openPostDetail(image.id)"
                >
                    <img :src="image.path" alt="Post Image" />
                </div>
            </div>
            <div v-else class="no-posts text-center pt-5">
                <p class="fw-bold">Share Photos</p>
                <p class="text-muted">
                    When you share photos, they will appear on your profile.
                </p>
                <a
                    href="/create-post"
                    class="share-first-photo text-primary fw-bold"
                    >Share your first photo</a
                >
            </div>
        </div>
        <PostDetailModal
            v-if="isModalOpen"
            :post="selectedPost"
            :isOpen="isModalOpen"
            @close="isModalOpen = false"
            @toggle-like="toggleLike"
            @add-comment="addComment"
        />
    </div>
</template>

<script>
import Sidenav from "@/Components/Sidenav.vue";
import PostDetailModal from "@/Components/PostDetailModal.vue";

export default {
    components: { Sidenav, PostDetailModal },
    props: {
        user: Object,
    },
    data() {
        return {
            isMobile: window.innerWidth <= 768,
            posts: this.user.posts,
            postsCount: this.user.posts.length,
            postImages: [],
            selectedPost: null,
            isModalOpen: false,
            feedPerRow: this.user.feed || 3,
        };
    },
    methods: {
        goToEditProfile() {
            this.$inertia.get("/profile/edit");
        },
        goToArchive() {
            this.$inertia.get("/posts/archived");
        },
        openPostDetail(postId) {
            this.$inertia.visit(`/posts/${postId}/detail`, {
                only: ["post"],
                preserveScroll: true,
                preserveState: true,
                onSuccess: (response) => {
                    this.selectedPost = response.props.post; // Update the post data
                    this.isModalOpen = true;
                },
                onError: (errors) => {
                    console.error("Error fetching post details:", errors);
                },
            });
        },
    },
    mounted() {
        if (Array.isArray(this.user.posts)) {
            this.postImages = this.user.posts.map((post) => ({
                id: post.id,
                path: post.path,
            }));
        } else if (this.user.posts && this.user.posts.original) {
            this.postImages = this.user.posts.original.posts.map((post) => ({
                id: post.id,
                path: post.path,
            }));
        }
        this.postsCount = this.postImages.length;
        window.addEventListener("resize", this.updateIsMobile);
    },
    beforeDestroy() {
        window.removeEventListener("resize", this.updateIsMobile);
    },
};
</script>

<style scoped>
.profile-page {
    display: flex;
    color: #000;
    padding-left: 220px;
}
.sidenav {
    flex-shrink: 0;
}
.profile-content {
    flex-grow: 1;
    max-width: 800px;
    margin: auto;
    color: #000;
}
.profile-header {
    display: flex;
    align-items: center;
}
.profile-header-top {
    display: flex;
    align-items: center;
    gap: 20px;
}
.profile-picture {
    width: 100px;
    height: 100px;
    object-fit: cover;
}
.edit-profile-btn,
.view-archive-btn {
    background: transparent;
    border: 1px solid #dbdbdb;
    padding: 5px 10px;
    border-radius: 5px;
    color: #000;
    cursor: pointer;
}
.profile-stats {
    font-size: 14px;
    margin-top: 10px;
}
.profile-posts {
    display: grid;
    gap: 5px;
}
.post-image img {
    object-fit: cover;
    object-position: center center;
    width: 250px;
    height: 250px;
}
.no-posts {
    text-align: center;
    margin-top: 50px;
}
.share-first-photo {
    color: #0095f6;
    font-weight: bold;
}

@media (max-width: 992px) {
    .profile-page {
        padding-left: 0;
    }
}
</style>
