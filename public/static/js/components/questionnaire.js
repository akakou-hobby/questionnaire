var calc_signature;

(async () => {
  const module = await import("../signature.js");
  calc_signature = module.calc_signature;
})();

const QuestionnairePage = {
  data() {
    return {
      data1: "",
    };
  },
  methods: {
    async answer() {
      const answers = JSON.stringify({ data1: this.data1 });
      const signature = await calc_signature(answers);
      const res = await axios.post("api/answer.php", {
        answers: answers,
        signature: signature,
      });
      console.log(res);
    },
  },
  template: `
    <div>
      <el-card>
        <h2>Questionnaire</h2>
        <el-form label-width="80px">
          <el-form-item label="Q1">
            <el-select name="1" v-model="data1">
              <el-option value="1">1</el-option>
              <el-option value="2">2</el-option>
              <el-option value="3">3</el-option>
              <el-option value="4">4</el-option>
            </el-select>
          </el-form-item >

          <el-form-item>
            <el-button v-on:click="answer">submit</el-button>
          </el-form-item>
        </el-form>
      </el-card>  
    </div>
    `,
};
