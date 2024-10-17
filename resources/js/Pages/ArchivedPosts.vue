<template>
    <div class="archive-section">
        <h2>Archived Posts</h2>
        <button class="back-button" @click="$inertia.visit('/dashboard')">
            ⬅ Back
        </button>

        <div class="card filter-section">
            <div class="card-body">
                <label for="start-date">Start Date</label>
                <input
                    type="date"
                    id="start-date"
                    v-model="filters.startDate"
                    class="form-control"
                />

                <label for="end-date">End Date</label>
                <input
                    type="date"
                    id="end-date"
                    v-model="filters.endDate"
                    class="form-control"
                />

                <button
                    class="btn btn-primary filter-button"
                    @click="fetchArchivedPhotos"
                >
                    Filter
                </button>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card">
            <div class="card-body">
                <table class="table archived-table">
                    <thead>
                        <tr>
                            <th>Foto/Video</th>
                            <th>Caption</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="photo in archivedPhotos" :key="photo.id">
                            <td>
                                <img
                                    v-if="isImage(photo.path)"
                                    :src="photo.path"
                                    alt="Image"
                                    class="preview-image"
                                />
                                <video
                                    v-else
                                    :src="photo.path"
                                    controls
                                    class="preview-video"
                                ></video>
                            </td>
                            <td>{{ photo.caption }}</td>
                            <td>{{ formatDate(photo.tanggal) }}</td>
                            <td>
                                <button
                                    class="btn btn-primary"
                                    @click="unarchivePost(photo.id)"
                                >
                                    Activate
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Export Buttons -->
        <div class="export-buttons">
            <button class="btn btn-success" @click="downloadAsXLSX">
                Download XLSX
            </button>
            <button class="btn btn-danger" @click="downloadAsPDF">
                Download PDF
            </button>
        </div>
    </div>
</template>

<script>
import * as XLSX from "xlsx";
import jsPDF from "jspdf";
import "jspdf-autotable";

export default {
    props: {
        archived_photos: Array,
        filtersFromQuery: {
            type: Object,
            default: () => ({ start_date: "", end_date: "" }),
        },
    },
    data() {
        return {
            filters: {
                startDate: this.filtersFromQuery.start_date,
                endDate: this.filtersFromQuery.end_date,
            },
        };
    },
    computed: {
        archivedPhotos() {
            return this.archived_photos;
        },
    },
    methods: {
        fetchArchivedPhotos() {
            const params = {};

            if (this.filters.startDate) {
                params.start_date = this.filters.startDate;
            }

            if (this.filters.endDate) {
                params.end_date = this.filters.endDate;
            }

            this.$inertia.get("/posts/archived", params).then((response) => {
                this.archivedPhotos = response.props.archived_photos;

                this.filters.startDate = response.props.filters.start_date || "";
                this.filters.endDate = response.props.filters.end_date || "";
            });
        },
        formatDate(date) {
            const options = { year: "numeric", month: "long", day: "numeric" };
            return new Date(date).toLocaleDateString(undefined, options);
        },
        isImage(path) {
            return /\.(jpg|jpeg|png|gif|webp)$/i.test(path);
        },
        unarchivePost(id) {
            this.$inertia.post(`/posts/unarchive/${id}`).then(() => {
                this.$inertia.visit("/archived");
            });
        },
        downloadAsXLSX() {
            const ws = XLSX.utils.json_to_sheet(this.archivedPhotos);
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "Archive Data");
            XLSX.writeFile(wb, "archive.xlsx");
        },
        downloadAsPDF() {
            const doc = new jsPDF();
            const columns = ["Foto/Video", "Caption", "Tanggal"];
            const data = this.archivedPhotos.map((photo) => [
                photo.path,
                photo.caption,
                this.formatDate(photo.tanggal),
            ]);

            doc.text("Archived Posts", 20, 10);
            doc.autoTable({
                head: [columns],
                body: data,
                startY: 20,
            });

            doc.save("archive.pdf");
        },
    },
};
</script>

<style scoped>
.archive-section {
    padding: 20px;
}

h2 {
    text-align: center;
    margin-bottom: 20px;
    color: black;
}

.card {
    margin-bottom: 20px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.filter-section {
    padding: 20px;
}

.form-control {
    margin-bottom: 10px;
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
}

.table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

.preview-image {
    width: 100px;
    height: 100px;
    object-fit: cover;
}

.preview-video {
    width: 150px;
    height: 100px;
}

.export-buttons {
    text-align: center;
    color: red;
}

.export-buttons:hover {
    text-align: center;
    color: rgb(190, 0, 0);
}

.btn {
    
    background-color: #3897f0;
    color: white;
    border: none;
    padding: 5px 12px;
    border-radius: 5px;
    font-size: 1em;
    cursor: pointer;
    margin-right: 10px;
}

.btn:hover {
    background-color: #005a9e;
}

th,
td,
label {
    color: black;
    padding: 10px;
    text-align: center;
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
