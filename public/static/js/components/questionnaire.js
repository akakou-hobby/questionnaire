var calc_signature;

(async () => {
  const module = await import("../signature.js");
  calc_signature = module.calc_signature;
})();

Vue.component("questionnaire-form", {
  data() {
    return {
      data1: "",
    };
  },
  methods: {
    async answer() {
      const answers = JSON.stringify({ data1: this.data1 });
      const signature = await calc_signature(answers);
      const res = await axios.post("api/answer", {
        answers: answers,
        signature: signature,
      });
      console.log(res);
    },
  },
  template: `
    <div>
        <h2>Questionnaire</h2>
            <label>Q1. hogehoge</label>
            <select name="1" v-model="data1">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
            </select>
            <button v-on:click="answer">submit</button>
    </div>
    `,
});
