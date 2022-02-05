<template>
  <div>
    <div class="modal-body" v-if="check==5">
      <p class="h4 text-right" data-dismiss="modal" @click="resetInf">
        <i class="fas fa-times-square"></i>
      </p>
      <div class="col-12 text-center">
        <p v-if="allFollowing.length==0" class="text-center">نەبووەتە شوێنكەوتەی كەس</p>
        <div class="col-12 p-0 follow">
          <div
            class="col-12 mx-auto text-center p-2 mt-2 border-bottom"
            v-for="(follower,index) in allFollowing"
            :key="index"
          >
            <router-link :to="{name:'peopleProfile',params:{name:follower.people.name}}">
              <div class="col-12 col-sm-8 mx-auto text-center">
                <img
                  src="/wallpaper/images.png"
                  class="col-4 col-sm-3 rounded-circle"
                  v-if="follower.people.path==null"
                />
                <img
                  :src="'/wallpaper/'+follower.people.path"
                  class="col-4 col-sm-3 rounded-circle"
                  v-if="follower.people.path!=null"
                />
                <br />
                <p class="d-inline">{{follower.people.name}}</p>
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
  props: ["allFollowing", "check", "pageFollowing"],
  methods: {
    resetInf() {
      this.$emit("restInfFollowing");
    },
    takeName(name) {
      this.$emit("name", name);
    }
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