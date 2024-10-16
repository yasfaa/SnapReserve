<template>
    <div class="profile-page d-flex">
        <!-- Sidenav berada di sebelah kiri -->
        <Sidenav v-show="!isMobile" class="sidenav" />

        <!-- Konten Profil -->
        <div class="profile-content flex-grow-1">
            <div class="profile-header ms-3 py-6 d-flex">
                <img
                    :src="user.path || 'http://www.gravatar.com/avatar/?d=mp'"
                    alt="Profile Picture"
                    class="profile-picture me-4 rounded-circle"
                />
                <div class="profile-info">
                    <div class="profile-header-top">
                        <h2>{{ user.name }}</h2>
                        <div class="d-flex mt-2">
                            <button @click="goToEditProfile" class="edit-profile-btn me-2">
                                Edit Profile
                            </button>
                            <button @click="goToArchive" class="view-archive-btn">
                                View Archive
                            </button>
                        </div>
                    </div>
                    <div class="profile-stats mt-3">
                        <span>{{ user.bio }} </span>
                    </div>
                </div>
            </div>

            <!-- Konten Postingan -->
            <div v-if="postsCount > 0" class="profile-posts pt-5" :style="{ gridTemplateColumns: `repeat(${feedPerRow}, 1fr)` }">
                <div v-for="(image, index) in postImages" :key="index" class="post-image">
                    <img :src="image" alt="Post Image" />
                </div>
            </div>
            <div v-else class="no-posts text-center pt-5">
                <p class="fw-bold">Share Photos</p>
                <p class="text-muted">When you share photos, they will appear on your profile.</p>
                <a href="/create-post" class="share-first-photo text-primary fw-bold">Share your first photo</a>
            </div>
        </div>
    </div>
</template>

<script>
import Sidenav from "@/Components/Sidenav.vue";

export default {
    components: { Sidenav },
    props: {
        user: Object,
        postsCount: Number,
        postImages: Array,
        feedPerRow: { type: Number, default: 3 },
    },
    data() {
        return {
            isMobile: window.innerWidth <= 768,
        };
    },
    methods: {
        goToEditProfile() {
            this.$inertia.get("/profile/edit");
        },
        goToArchive() {
            this.$inertia.get("/profile/archive");
        },
    },
    mounted() {
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
    padding-left: 220px; /* Tambahkan padding untuk menyesuaikan Sidenav */
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
    width: 100%;
    height: auto;
}
.no-posts {
    text-align: center;
    margin-top: 50px;
}
.share-first-photo {
    color: #0095f6;
    font-weight: bold;
}

/* Media query untuk menyesuaikan padding di desktop */
@media (max-width: 992px) {
    .profile-page {
        padding-left: 0;
    }
}
</style>
