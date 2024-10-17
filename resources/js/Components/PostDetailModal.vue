<template>
    <div v-if="isOpen" class="modal-overlay" @click.self="closeModal">
        <div class="modal-content">
            <button class="close-button" @click="$inertia.visit('/dashboard')">
                &times;
            </button>
            <button @click="archivePost(post.id)" class="archive-button">
                Arsipkan
            </button>
            <div class="left-section">
                <img
                    :src="post.image_url"
                    alt="Post Image"
                    class="full-image"
                />
            </div>
            <div class="right-section">
                <div class="post-header">
                    <img
                        :src="
                            post.profile_image_url ||
                            'http://www.gravatar.com/avatar/?d=mp'
                        "
                        alt="Profile"
                        class="profile-image"
                    />
                    <span class="username">{{ post.username }}</span>
                </div>
                <div class="caption">
                    {{ post.caption }}
                </div>
                <div class="scrollable-section">
                    <div class="comments-section">
                        <div
                            v-for="comment in post.comments"
                            :key="comment.id"
                            class="comment"
                        >
                            <strong>{{ comment.username }}:</strong>
                            {{ comment.comment }}
                        </div>
                    </div>
                </div>
                <div class="like-section">
                    <button @click="toggleLike(post.id)" class="like-button">
                        <span v-if="post.has_liked">♥</span>
                        <span v-else>♡</span>
                    </button>
                    <span>{{ post.like_count }} likes</span>
                </div>
                <div class="comment-input-section">
                    <input
                        type="text"
                        v-model="newComment"
                        placeholder="Tambahkan komentar..."
                        class="comment-input"
                    />
                    <button
                        @click="addComment(post.id)"
                        class="post-comment-button"
                    >
                        Post
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["post", "isOpen"],
    data() {
        return {
            newComment: "",
        };
    },
    methods: {
        closeModal() {
            this.$emit("close");
        },
        toggleLike(postId) {
            this.$inertia.post(
                `/post/${postId}/like`,
                {},
                {
                    preserveScroll: true,
                    onSuccess: () => {
                        this.post.has_liked = !this.post.has_liked;
                        this.post.like_count += this.post.has_liked ? 1 : -1;
                    },
                }
            );
        },
        addComment(postId) {
            if (this.newComment.trim()) {
                this.$inertia.post(
                    `/post/${postId}/comment`,
                    {
                        comment: this.newComment,
                    },
                    {
                        preserveScroll: true,
                        onSuccess: () => {
                            this.newComment = "";
                        },
                    }
                );
            }
        },
        archivePost(id) {
            this.$inertia.post(`/posts/archive/${id}`).then(() => {
                this.$inertia.visit("/profile");
            });
        },
    },
};
</script>

<style scoped>
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.modal-content {
    display: flex;
    width: 80%;
    max-width: 900px;
    max-width: 800px;
    background: white;
    border-radius: 8px;
    overflow: hidden;
    position: relative;
}

.close-button {
    position: absolute;
    top: 10px;
    right: 10px;
    background: none;
    border: none;
    font-size: 2em;
    color: #333;
    cursor: pointer;
}

.archive-button {
    position: absolute;
    top: 15px;
    right: 30px;
    background-color: #3897f0;
    color: white;
    border: none;
    padding: 4px 7px;
    border-radius: 5px;
    font-size: 1em;
    cursor: pointer;
}

.left-section {
    flex: 1;
    background-color: black;
    display: flex;
    align-items: center;
    justify-content: center;
}

.full-image {
    width: 100%;
    height: auto;
    object-fit: contain;
}

.right-section {
    flex: 0.6;
    display: flex;
    flex-direction: column;
    padding: 20px;
}

.post-header {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

.profile-image {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    margin-right: 10px;
}

.username {
    font-weight: bold;
    color: #333;
}

.caption {
    margin-bottom: 15px;
    color: #555;
}

.scrollable-section {
    flex-grow: 1;
    overflow-y: auto;
    margin-bottom: 15px;
}

.comments-section {
    max-height: 200px;
}

.comment {
    margin-bottom: 10px;
}

.like-section {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

.like-button {
    background: none;
    border: none;
    font-size: 1.5em;
    color: #e74c3c;
    cursor: pointer;
    margin-right: 10px;
}

.like-button span {
    font-size: 1.5em;
}

.comment-input-section {
    display: flex;
    align-items: center;
    border-top: 1px solid #ddd;
    padding-top: 10px;
}

.comment-input {
    flex: 1;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    outline: none;
}

.post-comment-button {
    background-color: #3897f0;
    color: white;
    border: none;
    padding: 8px 12px;
    margin-left: 10px;
    border-radius: 5px;
    cursor: pointer;
}
</style>
