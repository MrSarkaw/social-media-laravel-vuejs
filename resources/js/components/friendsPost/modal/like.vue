<template>
  <div>
    <div class="modal-body"  v-if="check==1">
      <p class="h4 text-right" @click="resetInf">
        <i class="fas fa-times-square"></i>
      </p>
      <p v-if="likes.length==0" class="text-center">هیچ لایكێك بوونی نییە</p>
      <div class="col-12 likeSec">
           <div
          class="col-12 mx-auto text-center p-2 mt-2 divLike"
          v-for="(like,index) in likes"
          :key="index"
         >
          <div class="col-8 col-sm-8 mx-auto text-center">
            <img 
            src="/wallpaper/images.png" 
            class="col-4 col-sm-3 rounded-circle"
            v-if="like.user.path==null" />
          <img 
            :src="'/wallpaper/'+like.user.path" 
            class="col-4 col-sm-3 rounded-circle"
            v-if="like.user.path!=null" />
              <p class="d-inline" @click="changeRoute(like.user.name)">{{like.user.name}}</p>
          </div>
        </div>
        <slot></slot>       
      </div>
    </div>
  
  </div>
</template>
<script>
export default {
  props: ["check", "likes", "pagelike"],
  methods: {
    resetInf() {
      this.$emit("resetInf");
    },
    changeRoute(name){
      this.$emit('name',name);
    }
  }
};
</script>

<style scoped>
  .likeSec{
    height: 300px;
    overflow: scroll;
    overflow-x: hidden;
  }
  .likeSec::-webkit-scrollbar{
    background: none;
    width: 10px;
  }
  .likeSec::-webkit-scrollbar-thumb{
    width: 5px;
    background-color: #bebcbc;
    border-radius: 20px;
  }
  @media only screen and(max-width:414px){
    .likeSec p{
      font-size: 10px;
    }
  }
</style>