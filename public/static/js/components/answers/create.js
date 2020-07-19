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
    ) => {
      const answers = JSON.stringify(this.data);
      const signature = await calcSignature(answers, this.$route.params.id);
      const res = await axios.post("api/answers/create.php", {
        answers: answers,
        signature: signature,
        questionnaire: this.$route.params.id,
      });
      alert("done");
    },
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
    };
  },
  created: async function () {
    const res = await axios.get(
      `api/questionnaires/show.php?id=${this.$route.params.id}`
    );
    this.data = res.data;
    console.log(this.data);
  },
  methods: {
    async answer() {
      const ui = new firebaseui.auth.AuthUI(firebase.auth());
      ui.start("#firebaseui-auth-container", authUIConfig);
    },
  },
  template: `
    <div>
      <el-card class="main">
        <div slot="header">
          <span>Form</span>
        </div>
        <el-form label-width="80px">  
          <el-form-item v-for="(pair, index) in data">          
            <el-card>
              <p>Q{{index + 1}}. {{pair.question}}</p>
              <el-input v-model="pair.answer">submit</el-input>
            </el-card>
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
