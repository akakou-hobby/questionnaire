const ShowAnswersPage = {
  data() {
    return {
      answers: [],
      url: "",
    };
  },

  created: async function () {
    const res = await axios.get(
      `api/answers/show.php?admin_token=${this.$route.params.id}`
    );

    console.log(res);

    this.answers = analysis(res.data.answers);
    this.url = location.href.split("#")[0] + "#/" + res.data.user_token;

    console.log(this.answers);
  },
  template: `
    <div>
      <el-card>
        <div slot="header">
          <span>Answers ({{url}})</span>
        </div>

        <div v-for="(answer, index) in answers">
          <el-card>
            Q{{index + 1}}. {{ answer.question }} <br>
            A{{index + 1}}. {{ answer.answer }} <br>
            Count: {{ answer.count }}
          </el-card>
          <br>
        </div>
      </el-card>
    </div>
    `,
};
