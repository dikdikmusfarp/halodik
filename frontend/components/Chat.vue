<template>
  <div class="container">
    <div class="row clearfix">
      <div class="col-lg-12">
        <div class="card chat-app">
          <div id="plist" class="people-list">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">
                  <BIconSearch></BIconSearch>
                </span>
              </div>
              <input type="text" @input="debounceSearch" class="form-control" placeholder="Search..."
                v-model="attributes.search">
            </div>
            <ul class="list-unstyled chat-list mt-2 mb-0">
              <li class="clearfix" v-for=" (item, index) in users" :key="index"
                v-on:click="getChat(item.id, item.name, item.is_online, item.updated_at)">
                <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="avatar">
                <div class="about">
                  <div class="name">{{ item ? item.name : " " }}</div>
                  <div v-if="item">
                    <div v-if="item.is_online" class="status"><b-icon-chat-left-dots-fill
                        variant="success"></b-icon-chat-left-dots-fill> online</div>
                    <div v-if="!item.is_online" class="status"><b-icon-chat-left-dots-fill
                        variant="secondary"></b-icon-chat-left-dots-fill> offline</div>
                  </div>
                </div>
              </li>
            </ul>
          </div>
          <div class="chat" v-if="chats">
            <div class="chat-header clearfix">
              <div class="row">
                <div class="col-lg-6">
                  <a href="javascript:void(0);" data-toggle="modal" data-target="#view_info">
                    <img src="https://bootdey.com/img/Content/avatar/avatar2.png" alt="avatar">
                  </a>
                  <div class="chat-about">
                    <h6 class="m-b-0">{{ room.name }}</h6>
                    <small>Last seen: {{ room.last_online }}</small>
                  </div>
                </div>
                <!-- <div class="col-lg-6 hidden-sm text-right">
                  <a href="javascript:void(0);" class="btn btn-outline-secondary"><i class="fa fa-camera"></i></a>
                  <a href="javascript:void(0);" class="btn btn-outline-primary"><i class="fa fa-image"></i></a>
                  <a href="javascript:void(0);" class="btn btn-outline-info"><i class="fa fa-cogs"></i></a>
                  <a href="javascript:void(0);" class="btn btn-outline-warning"><i class="fa fa-question"></i></a>
                </div> -->
              </div>
            </div>
            <div class="chat-history">
              <ul class="m-b-0">
                <li class="clearfix" v-for=" (item, index) in chats" :key="index">
                  <div class="message-data text-right" v-if="item.id_pengirim == user.id">
                    <span class="message-data-time">{{ item.waktu_pengiriman }}, {{ item.read_status ? "Read" :
                      "Delivered" }}</span>
                    <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="avatar">
                  </div>
                  <div class="message other-message float-right" v-if="item.id_pengirim == user.id"> {{ item.isi }}
                  </div>
                  <div class="message-data" v-if="item.id_pengirim != user.id">
                    <span class="message-data-time">{{ item.waktu_pengiriman }}</span>
                  </div>
                  <div class="message my-message" v-if="item.id_pengirim != user.id">{{ item.isi }}</div>
                </li>
              </ul>
            </div>
            <div class="chat-message clearfix">
              <div class="input-group mb-0">
                <input type="text" v-model="form.isi" :class="{ 'is-invalid': $v.form.isi.$error }" class="form-control"
                  placeholder="Enter text here...">
                <div class="input-group-prepend" v-on:click="submit()">
                  <span class="input-group-text">
                    <BIconArrowRightCircleFill></BIconArrowRightCircleFill>
                  </span>
                </div>
                <div v-if="$v.form.isi.$error" class="invalid-feedback">
                  <span v-if="!$v.form.isi.required">Harus isi pesan.</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { BIconChatLeftDotsFill, BIconArrowRightCircleFill, BIconSearch } from 'bootstrap-vue'
import { required } from "vuelidate/lib/validators";

export default {
  components: {
    BIconChatLeftDotsFill,
    BIconArrowRightCircleFill,
    BIconSearch,
  },
  data() {
    return {
      form: {
        isi: null,
        penerima: null,
      },
      room: {
        name: null,
        last_online: null,
        online: null,
        waktu: null,
      },
      user: null,
      users: null,
      chats: null,
      attributes: {
        search: null,
      },
    };
  },
  validations: {
    form: {
      // BAGIAN PELAPOR
      isi: {
        required
      },
    },
  },
  methods: {
    async getContact() {
      let param = {
        search: this.attributes.search,
      }
      this.$store
        .dispatch("user/getContact", { params: param })
        .then((resp) => {
          this.users = resp.data
        })
        .catch((error) => {
        });
    },
    getChat(id, nama, online, waktu) {
      this.room.name = nama
      this.form.penerima = id
      this.room.online = online
      this.room.waktu = waktu
      if (!online) {
        this.room.last_online = waktu
      } else {
        this.room.last_online = "online"
      }
      this.$store.dispatch("chat/getChat", { id: id })
        .then((resp) => {
          this.chats = resp.data
        })
        .catch((error) => {
        });
    },
    submit() {
      this.$v.$touch();
      if (this.$v.$invalid) {
      }
      else {
        let form = this.form;
        this.$store
          .dispatch("chat/store", { form })
          .then((resp) => {
            this.form.isi = null
            this.getChat(this.form.penerima)
            this.$v.$reset();
          })
          .catch((error) => {
          });
      }
    },
    debounceSearch(val) {
      clearTimeout(this.debounce)
      this.debounce = setTimeout(() => {
        this.getContact()
      }, 600)
    },
  },
  watch: {
    'form.penerima': function (newVal, oldVal) {
      this.intervalChat = setInterval(() => {
        this.getChat(this.form.penerima, this.room.name, this.room.online, this.room.waktu );
      }, 5000);
    },
  },
  async created() {
    this.user = this.$store.state.auth.user
    await this.getContact();
    this.intervalId = setInterval(this.getContact, 5000);
  },
  beforeDestroy() {
    // Hentikan interval saat komponen dihancurkan
    clearInterval(this.intervalId);
    clearInterval(this.intervalChat);
  },
}
</script>

<style scoped>
body {
  background-color: #f4f7f6;
  margin-top: 20px;
}

.card {
  background: #fff;
  transition: .5s;
  border: 0;
  margin-bottom: 30px;
  border-radius: .55rem;
  position: relative;
  width: 100%;
  box-shadow: 0 1px 2px 0 rgb(0 0 0 / 10%);
}

.chat-app .people-list {
  width: 280px;
  position: absolute;
  left: 0;
  top: 0;
  padding: 20px;
  z-index: 7
}

.chat-app .chat {
  margin-left: 280px;
  border-left: 1px solid #eaeaea
}

.people-list {
  -moz-transition: .5s;
  -o-transition: .5s;
  -webkit-transition: .5s;
  transition: .5s
}

.people-list .chat-list li {
  padding: 10px 15px;
  list-style: none;
  border-radius: 3px
}

.people-list .chat-list li:hover {
  background: #efefef;
  cursor: pointer
}

.people-list .chat-list li.active {
  background: #efefef
}

.people-list .chat-list li .name {
  font-size: 15px
}

.people-list .chat-list img {
  width: 45px;
  border-radius: 50%
}

.people-list img {
  float: left;
  border-radius: 50%
}

.people-list .about {
  float: left;
  padding-left: 8px
}

.people-list .status {
  color: #999;
  font-size: 13px
}

.chat .chat-header {
  padding: 15px 20px;
  border-bottom: 2px solid #f4f7f6
}

.chat .chat-header img {
  float: left;
  border-radius: 40px;
  width: 40px
}

.chat .chat-header .chat-about {
  float: left;
  padding-left: 10px
}

.chat .chat-history {
  padding: 20px;
  border-bottom: 2px solid #fff
}

.chat .chat-history ul {
  padding: 0
}

.chat .chat-history ul li {
  list-style: none;
  margin-bottom: 30px
}

.chat .chat-history ul li:last-child {
  margin-bottom: 0px
}

.chat .chat-history .message-data {
  margin-bottom: 15px
}

.chat .chat-history .message-data img {
  border-radius: 40px;
  width: 40px
}

.chat .chat-history .message-data-time {
  color: #434651;
  padding-left: 6px
}

.chat .chat-history .message {
  color: #444;
  padding: 18px 20px;
  line-height: 26px;
  font-size: 16px;
  border-radius: 7px;
  display: inline-block;
  position: relative
}

.chat .chat-history .message:after {
  bottom: 100%;
  left: 7%;
  border: solid transparent;
  content: " ";
  height: 0;
  width: 0;
  position: absolute;
  pointer-events: none;
  border-bottom-color: #fff;
  border-width: 10px;
  margin-left: -10px
}

.chat .chat-history .my-message {
  background: #efefef
}

.chat .chat-history .my-message:after {
  bottom: 100%;
  left: 30px;
  border: solid transparent;
  content: " ";
  height: 0;
  width: 0;
  position: absolute;
  pointer-events: none;
  border-bottom-color: #efefef;
  border-width: 10px;
  margin-left: -10px
}

.chat .chat-history .other-message {
  background: #e8f1f3;
  text-align: right
}

.chat .chat-history .other-message:after {
  border-bottom-color: #e8f1f3;
  left: 93%
}

.chat .chat-message {
  padding: 20px
}

.online,
.offline,
.me {
  margin-right: 2px;
  font-size: 8px;
  vertical-align: middle
}

.online {
  color: #86c541
}

.offline {
  color: #e47297
}

.me {
  color: #1d8ecd
}

.float-right {
  float: right
}

.clearfix:after {
  visibility: hidden;
  display: block;
  font-size: 0;
  content: " ";
  clear: both;
  height: 0
}

@media only screen and (max-width: 767px) {
  .chat-app .people-list {
    height: 465px;
    width: 100%;
    overflow-x: auto;
    background: #fff;
    left: -400px;
    display: none
  }

  .chat-app .people-list.open {
    left: 0
  }

  .chat-app .chat {
    margin: 0
  }

  .chat-app .chat .chat-header {
    border-radius: 0.55rem 0.55rem 0 0
  }

  .chat-app .chat-history {
    height: 300px;
    overflow-x: auto
  }
}

@media only screen and (min-width: 768px) and (max-width: 992px) {
  .chat-app .chat-list {
    height: 650px;
    overflow-x: auto
  }

  .chat-app .chat-history {
    height: 600px;
    overflow-x: auto
  }
}

@media only screen and (min-device-width: 768px) and (max-device-width: 1024px) and (orientation: landscape) and (-webkit-min-device-pixel-ratio: 1) {
  .chat-app .chat-list {
    height: 480px;
    overflow-x: auto
  }

  .chat-app .chat-history {
    height: calc(100vh - 350px);
    overflow-x: auto
  }
}
</style>
