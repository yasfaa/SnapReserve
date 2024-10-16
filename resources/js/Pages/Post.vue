<template>
    <div class="upload-section">
        <button class="back-button" @click="$inertia.visit('/dashboard')">
            ⬅ Back
        </button>
        <div class="form-box">
            <h2>Upload Foto/Video</h2>
            <form @submit.prevent="submitForm">
                <div class="inputbox">
                    <label for="fileInput" class="custom-file-label"
                        >Pilih Foto/Video</label
                    >
                    <input
                        id="fileInput"
                        type="file"
                        class="form-control custom-file-input"
                        @change="handleFileChange"
                        accept="image/*,video/*"
                    />
                </div>
                <!-- Preview for Image or Video -->
                <div v-if="previewUrl" class="preview-container">
                    <img
                        v-if="isImage"
                        :src="previewUrl"
                        alt="Preview"
                        class="preview-image"
                    />
                    <video
                        v-else
                        :src="previewUrl"
                        controls
                        class="preview-video"
                    ></video>
                </div>
                <div class="inputbox">
                    <label for="caption">Tambahkan caption</label>
                    <textarea
                        id="caption"
                        class="form-control"
                        v-model="caption"
                        placeholder="Caption"
                    ></textarea>
                </div>
                <button class="btn post-button" type="submit">Post</button>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            file: null,
            caption: "",
            previewUrl: null,
            isImage: false,
        };
    },
    methods: {
        handleFileChange(event) {
            this.file = event.target.files[0];
            if (this.file) {
                const fileType = this.file.type;
                this.isImage = fileType.startsWith("image");
                this.previewUrl = URL.createObjectURL(this.file);
            }
        },
        submitForm() {
            if (!this.file) {
                alert("Please choose a file to upload");
                return;
            }

            const formData = new FormData();
            formData.append("gambar", this.file);
            formData.append("caption", this.caption);

            this.$inertia.post("/posts/upload", formData).then(() => {
                this.$inertia.visit("/profile");
            });
        },
    },
};
</script>

<style scoped>
.upload-section {
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

.inputbox textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #3e3e3e;
    border-radius: 5px;
    outline: none;
    font-size: 1em;
}

textarea {
    height: 100px;
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

.preview-image,
.preview-video {
    max-width: 100%;
    max-height: 200px;
    border: 1px solid #ddd;
    border-radius: 10px;
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
