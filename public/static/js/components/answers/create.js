var calcSignature;

(async () => {
  const module = await import("../../signature.js");
  calcSignature = module.calcSignature;
})();

const authUIConfig = {
  signInFlow: "popup",
  callbacks: {
    signInSuccessWithAuthResult: async (
      { user, isNewUser, credential },
      redirectUrl
    ) => {},
    signInFailure: (error) => {
      alert(error);
    },
    uiShown: () => {},
  },
  signInOptions: [
    {
      provider: firebase.auth.PhoneAuthProvider.PROVIDER_ID,
      defaultCountry: "JP",
      whitelistedCountries: ["JP", "+81"],
    },
  ],
};

const CreateAnswerPage = {
  data() {
    return {
      data: {},
      count: 0,
    };
  },
  created: async function () {
    const res = await axios.get(
      `api/forms/show.php?user_token=${this.$route.params.id}`
    );
    this.data = res.data;
    console.log(this.data);
  },
  methods: {
    async answer() {
      const self = this;
      authUIConfig.callbacks.signInSuccessWithAuthResult = () => {
        self._answer();
      };

      const ui = new firebaseui.auth.AuthUI(firebase.auth());
      ui.start("#firebaseui-auth-container", authUIConfig);
    },

    async _answer() {
      const self = this;

      const answers = JSON.stringify(this.data.questions);
      const signature = await calcSignature(
        answers,
        this.$route.params.id,
        this.data.pubkey
      );

      const pid = setInterval(async () => {
        if (self.count > 0) {
          self.count--;
        } else {
          const res = await axios.post("api/answers/create.php", {
            answers: answers,
            signature: signature,
            user_token: this.$route.params.id,
          });

          alert(res.data);
          clearInterval(pid);
        }
      }, 1000);
    },
  },
  template: `
    <div>
      <el-card>
        <div slot="header">
          <span>Form</span>
        </div>
        <el-form label-width="80px">  
          <el-form-item v-for="(pair, index) in data.questions">          
            <p>Q{{index + 1}}. {{pair.question}}</p>
            <el-input v-model="pair.answer">submit</el-input>
          </el-form-item>

          <el-form-item>
            <el-input-number v-model="count"></el-input-number>
          </el-form-item>
          <el-form-item>
            <el-button v-on:click="answer">submit</el-button>
            <div id="firebaseui-auth-container"></div>
          </el-form-item>
          
        </el-form>
      </el-card>  
    </div>
    `,
};
