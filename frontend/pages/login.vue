<template>
  <div class="container">
    <div class="card">
      <div class="card-header">
        <h3>Login to your account</h3>
      </div>
      <div class="card-body">
        <form @submit.prevent="submitForm" enctype="multipart/form-data">
          <div class="row">
            <div class="col-xl-6 mt-2">
              <label class="required">Email</label> :
              <div>
                <input id="rounded" autocomplete="off" v-model="form.email" type="email" class="form-control" name="email"
                  :class="{ 'is-invalid': $v.form.email.$error }" placeholder="Masukkan email" />
                <div v-if="$v.form.email.$error" class="invalid-feedback">
                  <span v-if="!$v.form.email.required">Email harus diisi.</span>
                </div>
              </div>
            </div>
            <div class="col-xl-6 mt-2">
              <label class="required">Password</label> :
              <div>
                <b-form-group id="input-group-2" class="mb-3">
                  <div class="input-group" :class="{ 'is-invalid': $v.form.password.$error }">
                    <b-form-input id="append" autocomplete="off" v-model="form.password" class="form-control"
                      name="password" :disabled="loading" :type="password_type" placeholder="Masukkan Password Anda"
                      :class="{ 'is-invalid': $v.form.password.$error }"></b-form-input>
                    <div id="appends" class="input-group-text">
                      <a href="" @click.prevent="showPassword"><BIconEyeFill></BIconEyeFill></a>
                    </div>
                  </div>
                  <div v-if="$v.form.password.$error" class="invalid-feedback">
                    <span v-if="!$v.form.password.required">Password baru harus diisi. </span>
                  </div>
                </b-form-group>
              </div>
            </div>
            <div class="col-xl-2">
              <b-button variant="primary" type="submit">Login</b-button>
            </div>
            <div class="col-xl-2">
              <nuxt-link to="/register">
                <b-button variant="primary">Register</b-button>
              </nuxt-link>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { required, minLength, sameAs } from "vuelidate/lib/validators";
import { BIconHouseFill, BIconChatFill, BIconPersonCircle, BIconEyeFill } from 'bootstrap-vue'

export default {
  name: 'IndexPage',
  components: {
    BIconPersonCircle,
    BIconChatFill,
    BIconHouseFill,
    BIconEyeFill
  },
  data() {
    return {
      form: {
        email: null,
        password: null,
      },
      submit: false,
      password_type: 'password',
      password_icon: 'uil-eye-slash',
      loading: false,
    };
  },
  validations: {
    form: {
      // BAGIAN PELAPOR
      email: {
        required
      },
      password: {
        required,
      },
    },
  },
  methods: {
    showPassword() {
      if (this.password_type === 'password') {
        this.password_type = 'text'
        this.password_icon = 'uil-eye'
      } else {
        this.password_type = 'password'
        this.password_icon = 'uil-eye-slash'
      }
    },
    submitForm() {
      this.$v.$touch();
      if (this.$v.$invalid) {
        console.log("gagal");
        // Swal.fire("GAGAL!", "Form masih belum lengkap", "error");
      }
      else {
      console.log(this.form);
        let form = this.form;
        this.$store
          .dispatch("auth/login", { form })
          .then((resp) => {
            this.$router.push("/"); // redirect ke route /lapor
          })
          .catch((error) => {
            console.error('Error during login:', error);
          });
      }
    },
  },
  async created() {
    let token = this.$store.state.auth.token
    if (token) {
      this.$router.push("/"); // redirect ke route /lapor
    }
  }
}
</script>

<style scoped>
.container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
}

.card {
  width: 1000px;
  /* Sesuaikan lebar card sesuai kebutuhan */
  border: 1px solid #ccc;
  border-radius: 5px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
</style>

