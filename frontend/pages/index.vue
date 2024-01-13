<template>
  <div>
    <b-tabs justified nav-class="nav-tabs-custom" content-class="p-3 text-muted" v-model="tabIndex">
      <b-tab>
        <template v-slot:title>
            <b-icon-chat-fill></b-icon-chat-fill>
        </template>
        <div class="row">
          <div class="col-xl-12">
            <chat-component></chat-component>
          </div>
        </div>
      </b-tab>
      <b-tab>
        <template v-slot:title>
            <b-icon-house-fill></b-icon-house-fill>
        </template>
        <b-card-body>
          <!-- Content for Tab 1 -->
          <h4>Hello!</h4>
          <h5>created by <a href="https://www.linkedin.com/in/dikdikmusfar/" target="_blank" rel="noopener noreferrer">Dikdik Musfar</a></h5>
          <p>Check out my portfolio <a href="https://bit.ly/DikdikPortfolio" target="_blank" rel="noopener noreferrer">here</a></p>
        </b-card-body>
      </b-tab>
      <b-tab>
        <template v-slot:title>
            <b-icon-person-circle></b-icon-person-circle>
        </template>
        <b-card-body>
          <!-- Content for Tab 1 -->
          <div class="row">
            <div class="col-xl-2">
              Nama Pengguna
            </div>
            <div class="col-xl-3">
              : {{ user ? user.name : " " }}
            </div>
          </div>
          <div class="row">
            <div class="col-xl-2">
              Email
            </div>
            <div class="col-xl-3">
              : {{ user ? user.email : " " }}
            </div>
          </div>
          <div class="row mt-2">
            <div class="col-xl-2">
              <b-button variant="danger" v-on:click="logout()"><b-icon-power></b-icon-power> Logout</b-button>
            </div>
          </div>
        </b-card-body>
      </b-tab>
    </b-tabs>
  </div>
</template>

<script>
import { BIconHouseFill, BIconChatFill, BIconPersonCircle, BIconPower } from 'bootstrap-vue'
import ChatComponent from '~/components/Chat'


export default {
  name: 'IndexPage',
  middleware: 'authentication',
  components: {
    BIconPersonCircle,
    BIconChatFill,
    BIconHouseFill,
    BIconPower,
    ChatComponent,
  },
  data() {
    return {
      tabIndex: 1, // Set the default active tab index
      user: null,
      form: {
        status: false,
      }
    };
  },
  methods: {
    logout() {
      this.$store
          .dispatch("auth/logout", { })
          .then((resp) => {
            this.$router.push("/login"); // redirect ke route /lapor
          })
          .catch((error) => {
          });
    },
    async updateStatus() {
      let form = this.form
      this.$store
          .dispatch("auth/status", { form})
          .then((resp) => {
          })
          .catch((error) => {
          });
    },
    async getContact() {
      this.$store
          .dispatch("user/getContact", { })
          .then((resp) => {
          })
          .catch((error) => {
          });
    },
  },
  watch: {
    'form.status': function (newVal, oldVal) {
      this.updateStatus()
    },
  },
  async created() {
    this.user = this.$store.state.auth.user
    if (this.user) {
      localStorage.setItem('auth.is_online', JSON.stringify(true));
      this.form.status = true;
    }
    await this.getContact();

  },

  beforeDestroy() {
    // Hapus data dari local storage saat halaman ditutup
    if (this.user) {
      localStorage.removeItem('auth.is_online');
      this.form.status = false;
    }
  }
}
</script>

<style scoped>
</style>
