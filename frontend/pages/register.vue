<template>
  <div class="container">
    <div class="card">
      <div class="card-header">
        <h3>Register new account</h3>
      </div>
      <div class="card-body">
        <form @submit.prevent="submitForm" @reset="onReset" enctype="multipart/form-data">
          <div class="row">
            <div class="col-xl-6 mt-2">
              <label class="required">email</label> :
              <div>
                <input id="rounded" autocomplete="off" v-model="form.email" type="email" class="form-control" name="email"
                  :class="{ 'is-invalid': $v.form.email.$error }" placeholder="Masukkan email" />
                <div v-if="$v.form.email.$error" class="invalid-feedback">
                  <span v-if="!$v.form.email.required">Email harus diisi.</span>
                </div>
              </div>
            </div>
            <div class="col-xl-6 mt-2">
              <label class="required">Nama Pengguna</label> :
              <div>
                <input id="rounded" autocomplete="off" v-model="form.name" type="text" class="form-control" name="name"
                  :class="{ 'is-invalid': $v.form.name.$error }" placeholder="Masukkan name pengguna" />
                <div v-if="$v.form.name.$error" class="invalid-feedback">
                  <span v-if="!$v.form.name.required">Nama pengguna harus diisi.</span>
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
                    <span v-if="!$v.form.password.minLength">Password harus 8 karakter atau lebih. </span>
                    <span v-if="!$v.form.password.Kapital">Password harus terdapat 1 buah huruf kapital. </span>
                    <span v-if="!$v.form.password.Angka">Password harus terdapat 1 buah angka. </span>
                  </div>
                </b-form-group>
              </div>
            </div>
            <div class="col-xl-6 mt-2">
              <label class="required">Konfirmasi Password</label> :
              <div>
                <b-form-group id="input-group-2" class="mb-3">
                  <div class="input-group" :class="{ 'is-invalid': $v.form.password_confirmation.$error }">
                    <b-form-input id="append" autocomplete="off" v-model="form.password_confirmation" class="form-control"
                      name="password_confirmation" :disabled="loading" :type="password_type_confirmation"
                      placeholder="Masukkan Lagi Password Anda"
                      :class="{ 'is-invalid': $v.form.password_confirmation.$error }"></b-form-input>
                    <div id="appends" class="input-group-text">
                      <a href="" @click.prevent="showPasswordConfirmation"><BIconEyeFill></BIconEyeFill></a>
                    </div>
                  </div>
                  <div v-if="$v.form.password_confirmation.$error" class="invalid-feedback">
                    <span v-if="!$v.form.password_confirmation.required">Konfirmasi password harus diisi. </span>
                    <span v-if="!$v.form.password_confirmation.sameAsPassword">Konfirmasi Password harus sama dengan
                      password.</span>
                  </div>
                </b-form-group>
              </div>
            </div>
            <div class="col-xl-2">
              <b-button variant="primary" type="submit">Register</b-button>
            </div>
            <div class="col-xl-2">
              <nuxt-link to="/login">
                <b-button variant="primary">Back to Login</b-button>
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
        name: null,
        password: null,
        password_confirmation: null,
      },
      submit: false,
      password_type: 'password',
      password_icon: 'uil-eye-slash',
      password_type_confirmation: 'password',
      password_icon_confirmation: 'uil-eye-slash',
      loading: false,
    };
  },
  validations: {
    form: {
      // BAGIAN PELAPOR
      email: {
        required
      },
      name: {
        required
      },
      password: {
        required,
        minLength: minLength(8),
        Kapital: function (value) {
          const containsUppercase = /[A-Z]/.test(value)
          return containsUppercase
        },
        Angka: function (value) {
          const containsNumber = /[0-9]/.test(value)
          return containsNumber
        },
      },
      password_confirmation: {
        required,
        sameAsPassword: sameAs('password')
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
    showPasswordConfirmation() {
      if (this.password_type_confirmation === 'password') {
        this.password_type_confirmation = 'text'
        this.password_icon_confirmation = 'uil-eye'
      } else {
        this.password_type_confirmation = 'password'
        this.password_icon_confirmation = 'uil-eye-slash'
      }
    },
    submitForm() {
      this.$v.$touch();
      if (this.$v.$invalid) {
        // Swal.fire("GAGAL!", "Form masih belum lengkap", "error");
      }
      else {
        let form = this.form;
        this.$store
          .dispatch("auth/register", { form })
          .then((resp) => {
            console.log(resp);
            this.$router.push("/login"); // redirect ke route /lapor
          })
          .catch((error) => {
          });
      }
    },
    onReset(event) {
      event.preventDefault();
      // Reset our form values
      this.form.nama = null
      this.form.username = null
      this.form.password = null
      this.form.password_confirmation = null
      this.$v.$reset();
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

