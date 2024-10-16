<template>
    <main>
        <!-- <Navbar /> -->
        <section class="login-section">
            <div class="container">
                <div class="form-box" :class="{ 'register-form': isRegister }">
                    <div class="padding-container">
                        <div class="form-value">
                            <transition name="fade" mode="out-in">
                                <!-- Login Form -->
                                <form
                                    v-if="!isRegister"
                                    key="login-form"
                                    @submit.prevent="onSubmitLogin"
                                >
                                    <h2>Login</h2>
                                    <div class="inputbox">
                                        <input
                                            class="input-type"
                                            type="email"
                                            v-model="loginEmail"
                                            required
                                        />
                                        <label for="">Email</label>
                                    </div>
                                    <div class="inputbox">
                                        <input
                                            class="input-type"
                                            :type="
                                                showPasswordLogin
                                                    ? 'text'
                                                    : 'password'
                                            "
                                            v-model="loginPassword"
                                            required
                                        />
                                        <label for="">Password</label>
                                        <i
                                            class="mdi mdi-eye-outline toggle-password"
                                            @click="
                                                togglePasswordVisibility(
                                                    'login'
                                                )
                                            "
                                        >
                                        </i>
                                    </div>
                                    <button
                                        class="login-button"
                                        type="submit"
                                        style="color: white"
                                    >
                                        Login
                                    </button>
                                    <div class="register">
                                        <p>
                                            Belum Punya Akun?
                                            <a
                                                @click="toggleForm"
                                                style="cursor: pointer"
                                                >Register</a
                                            >
                                        </p>
                                    </div>
                                </form>

                                <!-- Register Form -->
                                <form
                                    v-else
                                    key="register-form"
                                    @submit.prevent="onSubmitRegister"
                                >
                                    <h2>Register</h2>
                                    <div class="inputbox">
                                        <input
                                            class="input-type"
                                            type="text"
                                            v-model="registerName"
                                            required
                                        />
                                        <label for="">Name</label>
                                    </div>
                                    <div class="inputbox">
                                        <input
                                            class="input-type"
                                            type="email"
                                            v-model="registerEmail"
                                            required
                                        />
                                        <label for="">Email</label>
                                    </div>
                                    <div class="inputbox">
                                        <input
                                            class="input-type"
                                            :type="
                                                showPasswordRegister
                                                    ? 'text'
                                                    : 'password'
                                            "
                                            v-model="registerPassword"
                                            required
                                        />
                                        <label for="">Create Password</label>
                                        <i
                                            class="mdi mdi-eye-outline toggle-password"
                                            @click="
                                                togglePasswordVisibility(
                                                    'register'
                                                )
                                            "
                                        >
                                        </i>
                                    </div>
                                    <div class="inputbox">
                                        <input
                                            class="input-type"
                                            :type="
                                                showPasswordRePassword
                                                    ? 'text'
                                                    : 'password'
                                            "
                                            v-model="registerRePassword"
                                            required
                                        />
                                        <label for="">Confirm Password</label>
                                        <i
                                            class="mdi mdi-eye-outline toggle-password"
                                            @click="
                                                togglePasswordVisibility(
                                                    'rePassword'
                                                )
                                            "
                                        >
                                        </i>
                                    </div>
                                    <button
                                        class="login-button"
                                        type="submit"
                                        style="color: white"
                                    >
                                        Register
                                    </button>
                                    <div class="register">
                                        <p>
                                            Sudah punya akun?
                                            <a
                                                @click="toggleForm"
                                                style="cursor: pointer"
                                                >Log in</a
                                            >
                                        </p>
                                    </div>
                                </form>
                            </transition>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</template>

<script>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
//   import Navbar from '@/Components/LoginNavbar.vue'

export default {
    // components: { Navbar },
    setup() {
        const isRegister = ref(false);
        const loginEmail = ref("");
        const loginPassword = ref("");
        const registerName = ref("");
        const registerEmail = ref("");
        const registerPassword = ref("");
        const registerRePassword = ref("");
        const showPasswordLogin = ref(false);
        const showPasswordRegister = ref(false);
        const showPasswordRePassword = ref(false);

        const form = useForm({
            email: "",
            password: "",
            name: "",
        });

        const toggleForm = () => {
            isRegister.value = !isRegister.value;
        };

        const togglePasswordVisibility = (type) => {
            if (type === "login")
                showPasswordLogin.value = !showPasswordLogin.value;
            if (type === "register")
                showPasswordRegister.value = !showPasswordRegister.value;
            if (type === "rePassword")
                showPasswordRePassword.value = !showPasswordRePassword.value;
        };

        const onSubmitLogin = () => {
            form.email = loginEmail.value;
            form.password = loginPassword.value;
            form.post("/login", {
                onSuccess: () => {
                    form.reset();
                },
            });
        };

        const onSubmitRegister = () => {
            if (registerPassword.value !== registerRePassword.value) {
                alert("Passwords do not match!");
                return;
            }
            form.name = registerName.value;
            form.email = registerEmail.value;
            form.password = registerPassword.value;

            form.post("/register", {
                onSuccess: () => {
                    form.reset();
                    toggleForm();
                },
            });
        };

        return {
            isRegister,
            loginEmail,
            loginPassword,
            registerName,
            registerEmail,
            registerPassword,
            registerRePassword,
            showPasswordLogin,
            showPasswordRegister,
            showPasswordRePassword,
            toggleForm,
            togglePasswordVisibility,
            onSubmitLogin,
            onSubmitRegister,
        };
    },
};
</script>

<style scoped>
.login-section {
    display: flex;
    justify-content: center;
    align-items: center;
    padding-top: 100px;
    width: 100%;
    background-position: center;
    background-size: cover;
}

.form-box {
    position: relative;
    width: 400px;
    height: auto;
    background: #f2f2f2;
    border: 1px solid rgba(51, 45, 24, 0.5);
    border-radius: 10px;
    display: flex;
    justify-content: center;
    align-items: center;
}

h2 {
    font-size: 2em;
    color: rgb(60, 60, 60);
    text-align: center;
}

.inputbox {
    position: relative;
    margin: 30px 0;
    width: 310px;
    border-bottom: 1px solid rgb(60, 60, 60);
}

.inputbox label {
    position: absolute;
    top: 50%;
    left: 5px;
    transform: translateY(-50%);
    color: rgb(60, 60, 60);
    font-size: 1em;
    pointer-events: none;
    transition: 0.5s;
}

.input-type:focus ~ label,
.input-type:valid ~ label {
    top: -5px;
}

.inputbox input {
    width: 100%;
    height: 50px;
    background: transparent;
    border: none;
    outline: none;
    font-size: 1em;
    padding: 0 35px 0 5px;
    color: rgb(60, 60, 60);
}

.login-button {
    width: 100%;
    height: 40px;
    border-radius: 40px;
    background: #2986CC;
    border: none;
    outline: none;
    cursor: pointer;
    font-size: 1em;
    font-weight: 600;
}

.login-button:hover {
    background-color: #7eb6e0;
    transition: background-color 0.5s;
}

.register {
    font-size: 0.9em;
    color: rgb(60, 60, 60);
    text-align: center;
    margin: 25px 0 10px;
}

.register p a {
    text-decoration: none;
    color: rgb(60, 60, 60);
    font-weight: 700;
}

.register p a:hover {
    text-decoration: underline;
}

.container {
    padding: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.padding-container {
    padding: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.slide-enter-active,
.slide-leave-active {
    transition: opacity 0.5s;
}

.slide-enter,
.slide-leave-to {
    opacity: 0;
}

.toggle-password {
    position: absolute;
    right: 5px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    font-size: 1.2em;
    color: rgb(60, 60, 60);
}
</style>
