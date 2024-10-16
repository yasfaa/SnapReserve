<template>
    <div class="sidenav text-uppercase">
        <ul>
            <li @click="profile">Profile</li>
            <li @click="upload">Post Foto</li>
            <li @click="logout" class="logout">Logout</li>
        </ul>
    </div>
</template>

<script>
export default {
    methods: {
        logout() {
            this.$inertia.post(
                "/logout",
                {},
                {
                    onSuccess: () => {
                        this.$inertia.visit("/");
                    },
                }
            );
        },
        profile() {
            this.$inertia.visit("/dashboard");
        },
        upload() {
            this.$inertia.visit("/create-post");
        },
    },
};
</script>

<style scoped>
.sidenav {
    position: fixed;
    left: 0;
    top: 0;
    height: 100vh;
    width: 200px;
    background-color: #e8e8e8;
    color: #000;
    padding-top: 20px;
    border-radius: 20px;
}
.sidenav ul {
    list-style: none;
    padding: 0;
    font-size: 1.2em;
}
.sidenav li {
    padding: 10px 20px;
    cursor: pointer;
}
.logout {
    position: absolute;
    bottom: 20px;
    width: 100%;
}
</style>
