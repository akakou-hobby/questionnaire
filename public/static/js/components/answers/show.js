const ShowAnswersPage = {
  data() {
    return { results: [] };
  },

  created: async function () {
    const res = await axios.get(
      `api/answers/show.php?id=${this.$route.params.id}`
    );
    this.results = analysis(res.data);
    console.log(this.results);
    console.log(analysis(res.data));
  },
  template: `
    <div>
      <el-card>
        <div slot="header">
          <span>Answers</span>
        </div>

        <div v-for="(result, index) in results">
          <el-card>
            Q{{index + 1}}. {{ result.question }} <br>
            A{{index + 1}}. {{ result.answer }} <br>
            Count: {{ result.count }}
          </el-card>
          <br>
        </div>
      </el-card>
    </div>
    `,
};
