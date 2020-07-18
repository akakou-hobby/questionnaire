var calcSignature;

(async () => {
  const module = await import("../../signature.js");
  calcSignature = module.calcSignature;
})();

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
      const answers = JSON.stringify(this.data);
      const signature = await calcSignature(answers, this.$route.params.id);
      const res = await axios.post("api/answers/create.php", {
        answers: answers,
        signature: signature,
        questionnaire: this.$route.params.id,
      });
      console.log(res);
    },
  },
  template: `
    <div>
      <el-card>
        <h2>Questionnaire</h2>
        <el-form label-width="80px">  
          <el-form-item v-for="pair in data">          
            <el-card>
              <p>{{pair.question}}</p>
              <el-input v-model="pair.answer">submit</el-input>
            </el-card>
          </el-form-item>

          <el-form-item>
            <el-button v-on:click="answer">submit</el-button>
          </el-form-item>
        </el-form>
      </el-card>  
    </div>
    `,
};
