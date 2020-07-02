const AuthPage = {
  data() {
    return {
      email: "",
      password: "",
    };
  },
  methods: {
    register() {
      firebase
        .auth()
        .createUserWithEmailAndPassword(this.email, this.password)
        .catch((error) => {
          alert(error.code);
          alert(error.message);
        });

      alert("registered!");
    },
  },
  template: `
    <div>
      <el-card>
        <h2>Register/Login</h2>

        <el-form label-width="80px">
          <el-form-item label="Email">
            <el-input v-model="email"></el-input>
          </el-form-item>

          <el-form-item label="Password">
            <el-input v-model="password"></el-input>
          </el-form-item>

          <el-form-item>
            <el-button v-on:click="register">register</el-button>
          </el-form-item>  
        </el-form>
      </el-card>
    </div>
    `,
};
