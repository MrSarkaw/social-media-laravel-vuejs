<template>
  <div>
    <div class="modal-body" v-if="check==4">
      <p class="h4 text-right" data-dismiss="modal" @click="resetInf">
        <i class="fas fa-times-square"></i>
      </p>
      <div class="col-12 text-center">
        <p v-if="allFollowers.length==0" class="text-center">هیچ شوێنكەوتویەكت نییە</p>
        <div class="col-12 p-0 follow">
          <div
            class="col-12 mx-auto text-center p-2 border-bottom"
            v-for="(follower,index) in allFollowers"
            :key="index"
              >
            <div class="col-12 col-sm-8 mx-auto text-center" 
            @click="takeName(follower.user.name)">
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
                <p class="d-inline">{{follower.user.name}}</p> <br>
            </div>
            <p class="text-left">
             <i class="fas fa-trash text-dark" @click="deleteFollow(follower.id)"></i>
            </p>
          </div>
          <slot></slot>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: ["allFollowers",'check','pageFollower'],
    methods: {
        resetInf(){
           this.$emit('restInfFollower');
        },
          takeName(name) {
            this.$emit("name", name);
          },
          deleteFollow(id){
            axios.delete('api/deleteMaFollower/'+id).then(()=>{
              Swal.fire('deleted');
            })
          }
    },
}



</script>

<style>
li {
  cursor: pointer;
  list-style-type: none;
  padding: 0px;
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