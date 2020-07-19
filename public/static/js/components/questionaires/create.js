const CreateQuestionnairePage = {
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
      const res = await axios.post("api/questionnaires/create.php", {
        data: this.data,
      });

      alert(res.data);
    },
  },
  template: `
    <div>
      <el-card class="main">
        <div slot="header">
          <span>New Form</span>
        </div>
        <el-form label-width="80px">
          <el-form-item v-for="(pair, index) in data">          
            Q.{{index + 1}} <el-input v-model="pair.question"></el-input>
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
