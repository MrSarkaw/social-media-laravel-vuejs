<template>
  <div>
    <br />
    <div class="col-12 p-0 mt-2 divChat col-sm-10 col-md-5 col-lg-4 mx-auto text-light">
      <div class="p-1 text-left border-bottom ">
        <router-link :to="{name:'peopleProfile',params:{name:name}}">
        <img
          src="/wallpaper/images.png"
          v-if="user.path==null"
          class="col-3 col-sm-2 col-md-2 rounded-circle"
          alt
        />
        <img
          :src="'/wallpaper/'+user.path"
          v-if="user.path !=null"
          class="col-3 col-sm-2 col-md-2 rounded-circle"
          alt
        />
        {{name}}
        </router-link>
      </div>
      <div class="p-0 col-12 messBox" v-chat-scroll="{always: false, smooth: true}">
        <infinite-loading direction="top" @infinite="loadUser">
            <div slot="no-more"></div>
            <div slot="no-results">
                <i class="fad fa-comments-alt p-5" style="font-size:60px"></i>
                </div>
        </infinite-loading>
        <div class="col-12" v-for="(chat,index) in orderedMess" :key="index">
          <div
            v-if="chat.sender_id==currentuser"
            class="col-7  p-1 mb-2  mt-1  cur animated slideInRight  float-right d-block"
          >
          <p class="text-center">{{chat.path}}</p>
          <small class="ml-2"> {{chat.created_at | time}}</small>
          </div>
          <div
            v-else
            class="col-7  mb-2 text-light mt-1 p-1   user animated slideInLeft float-left d-block"
          >
        <p class="text-center ">{{chat.path}}</p>
          <small class="ml-2"> {{chat.created_at | time}}</small>
          </div>
        </div>
      </div>
      <div class="col-12 p-2">
        <div class="inp text-light">
          <i class="fad fa-envelope-square ml-2"></i>
          <input type="text" class="ml-3" placeholder="message .." v-model="placeholder" />
        </div>
        <button class="mt-1 btn-sm btn-block btnBlue" @click="insertMessage" v-if="placeholder!=''">
          <i class="fad fa-paper-plane"></i>
        </button>
        <br />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      name: this.$route.params.name,
      user: [],
      chats: [],
      currentuser: "",
      message: "",
      placeholder: "",
      pageChat: 1
    };
  },
  computed: {
    orderedMess() {
      this.chats.sort((a, b) => {
        return new Date(a.created_at) - new Date(b.created_at);
      });
      return this.chats;
    }
  },
  methods: {
    loadUser($state) {
      axios
        .get("/api/chats/" + this.name, {
          params: {
            page: this.pageChat
          }
        })
        .then(data => {
            if(data.data[1]==null || data.data[1].id==data.data[2]){
                this.$router.push('/chat');
            }else{
                this.user = data.data[1];
                this.currentuser = data.data[2];
                let chats = data.data[0].data;
                if (chats.length) {
                    this.chats = this.chats.concat(chats);
                    $state.loaded();
                } else {
                    $state.complete();
                }
            }
         
        })
        .catch(() => {
          console.log("err");
        });
      this.pageChat++;
    },
    insertMessage() {
      (this.message = this.placeholder),
        (this.placeholder = ""),
        this.$Progress.start();
      axios
        .post("/api/chats", {
          reciver_id: this.user.id,
          path: this.message
        })
        .then(() => {
          this.message = "";
          this.$Progress.finish();
        })
        .catch(() => {
          this.$Progress.fail();
          console.log("error");
        });
    }
  },
  mounted() {
       Echo.private("chat").listen("chatEvent", e => {
      if (
        (e.message.sender_id == this.currentuser &&
          e.message.reciver_id == this.user.id) ||
        (e.message.reciver_id == this.currentuser &&
          e.message.sender_id == this.user.id)
      ) {
        this.chats.unshift(e.message);
      }
    });
  }
};
</script>

<style  scoped>
.divChat {
  background: #538ec8;
  border-radius: 10px;
  height: 430px;
}
.messBox {
  background: #141414;
  height: 265px;
  font-size: 10px;
  overflow: scrroll;
  overflow-x: hidden; background: #538ec8;
}
.messBox::-webkit-scrollbar {
  width: 10px;
  background: none;
}
.messBox::-webkit-scrollbar-thumb {
  width: 7px;
  background: #dfdfdf;
  border-radius: 10px;
}
.inp  {
  box-shadow: -2px -2px 3px rgb(255, 255, 255) inset,
    3px 3px 7px #000000e7 inset;
   
}
.cur{
    background: #538ec8;
    box-shadow:  -2px -2px 2px rgba(255, 255, 255, 0.575) inset,
                       3px 3px 5px #000000a4 inset;
    color:#e6e6e6;
    border-radius: 20px;
}
.user{
    background: #414141;
    box-shadow:  -2px -2px 2px rgba(255, 255, 255, 0.575) inset,
                       3px 3px 5px #000000a4 inset;
    color:#e6e6e6;    border-radius: 20px;

}
.btnBlue{
  background: #414141;
}
</style>