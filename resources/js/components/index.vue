<template>
  <div>
    <div 
    class="modal fade animated slideInUp" 
    data-backdrop="static"
    data-keyboard="false"
    id="search"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
              <p class="h4 text-right" @click="hideModal">
                <i class="fas fa-times-square"></i>
              </p>
            <div class="inp mx-auto">
              <i class="fad fa-search text-light"></i>
              <input
                type="text"
                placeholder="گەڕان"
                class="col-9 float-right text-right"
                v-model="search"
                @keyup="searching"
              />
            </div><br>
            <div class="col-12 text-center mx-auto   p-1" id="divResult" v-if="search!=''">
              <div v-for="(user,index) in result" :key="index" class="border-bottom s p-2 mt-2"
               @click="changeRoute(user.name)">
                <img :src="'wallpaper/'+user.path" class="col-2 col-sm-1 p-0 rounded-circle"
                v-if="user.path!=null" alt="">
                <img src="wallpaper/images.png" class="col-2 col-sm-1 p-0 rounded-circle"
                v-if="user.path==null" alt="">
                {{user.name}}
              </div>
              <infinite-loading @infinite="searching" ref="searchakan">
                <div slot="no-more"></div>
              </infinite-loading>
            </div>
          </div>
        </div>
      </div>
    </div>

    <p
      class="fixed-bottom float-left col-2 col-sm-1 ml-2 btn btn-primary"
      data-target="#search"
      data-toggle="modal"
    >
      <i class="fad fa-search text-light"></i>
    </p>

    <router-view></router-view>
  </div>
</template>

<script>
export default {
  data() {
    return {
      search: "",
      result: [],
      page: 1,
      check:''
    };
  },
  methods: {
    searching: _.debounce(function ($state) {
      if(this.search==''){
        this.result.length=0;
        this.page=1;
      }else{
          this.checkSearch(this.search);
          axios
        .get("/api/search/" + this.search, {
          params: {
            page: this.page
          }
        })
        .then(data => {
          let result = data.data.data;
          if (result.length) {
            console.log(result);
            this.result = this.result.concat(result);
            $state.loaded();
          } else {
            $state.complete();
          }
        })
        .catch(() => {});
      this.page++;
      }
    }, 1300),

    checkSearch(val){
      
      if(this.check!=val){
        if(this.result.length){
        this.$refs.searchakan.stateChanger.reset();
       }
        this.page=1;
        this.result.length=0;
        this.check=val;
      }
    },
    hideModal(){
      this.search='';
      this.check='';
      this.result.length=0;
      $('#search').modal('hide');
    },
    changeRoute(name){
      this.hideModal();
      this.$router.push({name:'peopleProfile',params:{name:name}})
    }
  }
};
</script>
<style scoped>
  #divResult{
    overflow: scroll;
    overflow-x: hidden;
    height: 250px;
  }
  #divResult::-webkit-scrollbar{
    width:10px;
    background: none;border-radius: 20px;
  }
  #divResult::-webkit-scrollbar-thumb{
    width:7px;
    background: #e6e6e6;
    border-radius: 20px;
  }
</style>