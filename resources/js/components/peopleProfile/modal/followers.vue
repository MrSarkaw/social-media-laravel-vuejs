<template>
  <div>
    <div class="modal-body" v-if="check==4">
      <p class="h4 text-right" data-dismiss="modal" @click="resetInf">
        <i class="fas fa-times-square"></i>
      </p>
      <div class="col-12 text-center">
        <p v-if="allFollowers.length==0" class="text-center"> كەس نەبووەتە شوێنكەوتەی </p>
        <div class="col-12 follow p-0">
          <div
            class="col-12 mx-auto text-center p-2 mt-2 border-bottom"
            v-for="(follower,index) in allFollowers"
            :key="index"
          >
            <router-link :to="{name:'peopleProfile',params:{name:follower.user.name}}">
              <div class="col-12 col-sm-8 mx-auto text-center" >
                <img
                  src="/wallpaper/images.png"
                  class="col-4 col-sm-3 rounded-circle"
                  v-if="follower.user.path==null"
                />
                <img
                  :src="'/wallpaper/'+follower.user.path"
                  class="col-4 col-sm-3 rounded-circle"
                  v-if="follower.user.path!=null"
                />
                <br />
                <p class="d-inline">{{follower.user.name}}</p>
                <br />
              </div>
            </router-link>
          </div>
          <slot></slot>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: ["allFollowers", "check", "pageFollower"],
  methods: {
    resetInf() {
      this.$emit("restInfFollower");
    },
    
  }
};
</script>

<style>
li {
  cursor: pointer;
  list-style-type: none;
  padding: 0px;
}
a{
  color: white;
}
.follow{
  height: 350px;
  overflow: scroll;
  overflow-x: hidden;
}
.follow::-webkit-scrollbar{
  width:10px;
  background: none;
}
.follow::-webkit-scrollbar-thumb{
  width:7px;
  border-radius: 20px;
  background: #f3f3f3;
}
</style>