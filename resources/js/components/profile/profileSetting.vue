<template>
  <div>
    <br />
    <div class="col-12 col-sm-9 col-md-6 mx-auto mb-5">
      <div class="col-12 p-2 form-group">
        <label class="float-right">
          گۆڕینی ناو
          <i class="fas fa-user-circle"></i>
        </label>
        <input
          type="text"
          class="col-11"
          v-model="form.name"
          :class="{'is-invalid':form.errors.has('name')}"
        />
        <has-error :form="form" field="name"></has-error>
      </div>
      <div class="col-12 p-2 form-group">
        <label class="float-right">
          گۆڕینی ئیمەیل
          <i class="fas fa-envelope-square"></i>
        </label>
        <input
          type="email"
          class="col-11"
          v-model="form.email"
          :class="{'is-invalid':form.errors.has('email')}"
        />
        <has-error :form="form" field="email"></has-error>
      </div>
      <div class="col-12 p-2 form-group">
        <label class="float-right">
          گۆڕینی وشەی نهێنی
          <i class="fas fa-lock"></i>
        </label>
        <input
          type="password"
          class="col-11"
          v-model="form.password"
          :class="{'is-invalid':form.errors.has('password')}"
        />
        <has-error :form="form" field="password"></has-error>
      </div>
      <div class="col-12 p-2 form-group">
        <label class="float-right">
          گۆڕینی بایۆ
          <i class="fas fa-pen-square"></i>
        </label>
        <textarea
          name
          id
          class="col-11"
          cols="30"
          rows="6"
          v-model="form.bio"
          :class="{'is-invalid':form.errors.has('bio')}"
        ></textarea>
        <has-error :form="form" field="bio"></has-error>
      </div>
      <button class="btn btn-sm btn-success col-4 float-right" @click="update">
        تازەكردنەوە
        <i class="fas fa-check-circle"></i>
      </button>
    </div>
    <br />
    <div class="col-12 col-sm-6 mt-3 mx-auto">
      <button class="btn btn-sm btn-primary" v-if="form.private==0" @click="changePrivate">
        <i class="fad fa-lock"></i> گۆڕین بۆ بینینی تایبەت
      </button>
      <button
        class="btn btn-sm btn-primary float-left"
        v-if="form.private==1"
        @click="changePrivate"
      >
        <i class="fad fa-globe-africa"></i> گۆڕین بۆ بینینی گشتی
      </button>
      <button class="btn btn-sm btn-danger float-right" @click="deleteAcc">
        <i class="fad fa-user-times"></i> سڕینەوەی هەژمار
      </button>
    </div>
    <br />
    <br />
  </div>
</template>
<script>
export default {
  data() {
    return {
      form: new Form({
        id: "",
        name: "",
        email: "",
        password: "",
        bio: "",
        private: ""
      })
    };
  },
  methods: {
    load() {
      axios.get("/api/profile").then(({ data }) => {
        this.form.fill(data[0][0]);
      });
    },
    takeName(e) {
      let reader = new FileReader();
      let file = e.target.files[0];
      reader.onloadend = file => {
        this.form.path = reader.result;
      };
      reader.readAsDataURL(file);
    },
    update() {
      this.formUpdate();
      this.$router.push("/profile");
    },
    changePrivate() {
      let checkPrivate = this.form.private;
      if (checkPrivate == 0) {
        this.form.private = 1;
      } else {
        this.form.private = 0;
      }
      this.formUpdate();
    },
    formUpdate() {
      this.$Progress.start();
      this.form
        .put("/api/updateProfile")
        .then(() => {
          this.$Progress.finish();
        })
        .catch(() => {
          this.$Progress.fail();
        });
    },
    deleteAcc() {
      this.form.delete("/api/users/" + this.form.id).then(() => {
        this.$router.push("/");
      });
    }
  },
  created() {
    this.load();
  }
};
</script>


<style scoped>
input,
textarea {
  background: none;
  outline: none;
  border: none;
  color: #c7c7c7;
}
label {
  color: #457fca;
}
.form-group {
  padding: 10px;
  box-shadow: -2px -2px 3px rgba(148, 148, 148, 0.637) inset,
    2px 2px 3px #000000 inset;
  border-radius: 17px;
}
</style>
    
