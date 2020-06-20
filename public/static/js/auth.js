Vue.component("auth-form", {
  data() {
    return {
      email: "",
      password: "",
    };
  },
  methods: {
    register(event) {
      console.log(this.email);
      console.log(this.password);
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
        </form>
    </div>
    `,
});
