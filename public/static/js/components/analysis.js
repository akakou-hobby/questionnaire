const AnalysisPage = {
  data() {
    return { results: [] };
  },

  created: async function () {
    const res = await axios.get("api/result.php");
    this.results = analysis(res.data);
    console.log(this.results);
    console.log(analysis(res.data));
  },
  template: `
    <div>
      <el-card>
        <h2>Analyis</h2>
          <el-card v-for="result in results">
            Q. {{ result.question }} <br>
            A. {{ result.answer }} <br>
            Count: {{ result.count }}
          </el-card>
      </el-card>
    </div>
    `,
};
