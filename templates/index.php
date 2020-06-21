<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>questionnaire</title>

    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>

    <script src="https://www.gstatic.com/firebasejs/6.2.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/6.2.0/firebase-auth.js"></script>
    <script src="https://www.gstatic.com/firebasejs/6.2.0/firebase-firestore.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.js"></script>

    <script src="./static/js/firebase.js"></script>
    <script src="./static/js/axios.js"></script>

    <script src="./static/js/components/auth.js"></script>

  </head>
  <body>
    <div id="auth">
      <auth-form></auth-form>
    </div>

    <script>
      new Vue({ el: '#auth' })

      fetch("bin/signature.wasm").then(response => response.arrayBuffer())
        .then(bytes => WebAssembly.instantiate(bytes, {}))
    	.then(results => {
          console.log(results.instance.exports.hoge(41));
      });
    </script>
  </body>
</html>
