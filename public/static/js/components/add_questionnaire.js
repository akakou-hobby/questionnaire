const AddQuestionnaire = {
  data() {
    return {
      data: [
        {
          question: "",
        },
      ],
    };
  },
  methods: {
    add() {
      this.data.push({
        question: "",
      });
    },
    async submit() {
      const res = await axios.post("api/add_questionnaire.php", {
        data: this.data,
      });

      alert(res.data);
    },
  },
  template: `
    <div>
      <el-card>
        <h2>Questionnaire</h2>
        <el-form label-width="80px">  
          <el-form-item v-for="pair in data">          
            <el-input v-model="pair.question">submit</el-input>
          </el-form-item>

          <el-form-item>
            <el-button v-on:click="add">add</el-button>
            <el-button v-on:click="submit">submit</el-button>
          </el-form-item>
        </el-form>
      </el-card>  
    </div>
    `,
};
