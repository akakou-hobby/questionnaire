Vue.component("auth-form", {
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
    async verify() {
      const res = await axios.post("/verify");
      console.log(res);
    },
  },
  template: `
    <div>
        <h2>Register</h2>
        <form action="#">
            <label>email</label>
            <input v-model="email"></input>
            <br>
            <label>password</label>
            <input v-model="password"></input>
            <br>
            <button v-on:click="register">register</button>
            <br>
            <button v-on:click="verify">verify</button>
        </form>
    </div>
    `,
});
