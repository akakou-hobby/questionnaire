const AuthPage = {
  data() {
    return {
      email: "",
      password: "",
    };
  },
  methods: {
    async register() {
      try {
        await firebase
          .auth()
          .createUserWithEmailAndPassword(this.email, this.password);

        router.push("questionnaire");
      } catch (e) {
        alert(e.message);
      }
    },

    async login() {
      try {
        await firebase
          .auth()
          .signInWithEmailAndPassword(this.email, this.password);

        router.push("questionnaire");
      } catch (e) {
        alert(e.message);
      }
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
            <el-button v-on:click="register" class="auth-button">register</el-button>
            <el-button v-on:click="login" class="auth-button">login</el-button>
          </el-form-item>  
        </el-form>
      </el-card>
    </div>
    `,
};
