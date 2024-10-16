<template>
    <div class="edit-profile-section">
        <Sidenav v-show="!isMobile" class="sidenav" />
        <div class="form-box">
            <h2>Edit Profile</h2>
            <form @submit.prevent="updateProfile">
                <div class="inputbox text-center">
                    <label for="fileInput" class="custom-file-label">
                        Change Profile Photo
                    </label>
                    <input
                        id="fileInput"
                        type="file"
                        class="form-control custom-file-input"
                        @change="onFileChange"
                        accept="image/*"
                    />
                    <div v-if="previewUrl" class="preview-container">
                        <img
                            v-if="previewUrl"
                            :src="previewUrl"
                            alt="Profile Preview"
                            class="preview-image"
                        />
                    </div>
                </div>

                <div class="inputbox">
                    <label for="name">Username</label>
                    <input
                        type="text"
                        v-model="form.name"
                        class="form-control"
                        placeholder="Enter username"
                        required
                    />
                </div>

                <div class="inputbox">
                    <label for="bio">Bio</label>
                    <textarea
                        id="bio"
                        v-model="form.bio"
                        class="form-control"
                        rows="3"
                        placeholder="Enter bio"
                    ></textarea>
                </div>

                <div class="inputbox">
                    <label for="feedPerRow">Photos Per Row</label>
                    <input
                        type="number"
                        v-model="form.feedPerRow"
                        class="form-control"
                        min="1"
                        max="5"
                        placeholder="Enter number of photos per row"
                    />
                </div>

                <button class="btn post-button" type="submit">
                    Save Changes
                </button>
            </form>
        </div>
    </div>
</template>

<script>
import Sidenav from "@/Components/Sidenav.vue";
export default {
    components: { Sidenav },
    props: {
        user: Object,
    },
    data() {
        return {
            isMobile: window.innerWidth <= 768,
            previewUrl: null,
            form: {
                name: this.user.name,
                bio: this.user.bio || "",
                feedPerRow: this.user.feed_per_row || 3,
                gambar: null,
            },
        };
    },
    methods: {
        onFileChange(event) {
            this.form.gambar = event.target.files[0];
            this.previewUrl = URL.createObjectURL(this.form.gambar)
        },
        updateProfile() {
            const formData = new FormData();
            formData.append("name", this.form.name);
            formData.append("bio", this.form.bio);
            formData.append("feedPerRow", this.form.feedPerRow);
            if (this.form.gambar) {
                formData.append("gambar", this.form.gambar);
            }

            this.$inertia.post("/profile/update", formData, {
                then: () => {
                    this.$inertia.visit("/profile");
                },
            });
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
.edit-profile-section {
    display: flex;
    justify-content: center;
    align-items: center;
    padding-top: 100px;
    width: 100%;
}

.form-box {
    width: 400px;
    padding: 20px;
    background: #f2f2f2;
    border: 1px solid rgba(51, 45, 24, 0.5);
    border-radius: 10px;
    text-align: center;
}

h2 {
    font-size: 2em;
    color: rgb(60, 60, 60);
    margin-bottom: 20px;
}

label {
    font-size: 1em;
    font-weight: bold;
    text-align: left;
    color: #3e3e3e;
}
.inputbox {
    margin-bottom: 20px;
    width: 100%;
    text-align: left;
}

.custom-file-label {
    display: block;
    font-size: 1em;
    color: white;
    background-color: #0072ce;
    padding: 10px;
    text-align: center;
    border-radius: 5px;
    cursor: pointer;
}

.custom-file-input {
    display: none;
}

.inputbox textarea,
.inputbox input {
    width: 100%;
    padding: 10px;
    border: 1px solid #3e3e3e;
    border-radius: 5px;
    outline: none;
    font-size: 1em;
}

.post-button {
    width: 100%;
    padding: 10px;
    font-size: 1.1em;
    font-weight: bolder;
    background-color: #0072ce;
    color: white;
    border: none;
    border-radius: 40px;
    cursor: pointer;
}

.post-button:hover {
    background-color: #005a9e;
}

.preview-container {
    margin-top: 15px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.preview-image {
    max-width: 100px;
    max-height: 100px;
    border: 1px solid #ddd;
    border-radius: 50%;
    object-fit: cover;
}

.back-button {
    position: absolute;
    top: 20px;
    left: 20px;
    background-color: transparent;
    border: none;
    color: #0072ce;
    font-size: 1em;
    font-weight: bold;
    cursor: pointer;
}
</style>
