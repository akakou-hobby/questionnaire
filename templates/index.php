<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>questionnaire</title>

    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>

    <script src="https://www.gstatic.com/firebasejs/6.2.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/6.2.0/firebase-auth.js"></script>
    <script src="https://www.gstatic.com/firebasejs/6.2.0/firebase-firestore.js"></script>

    <script src="./static/js/firebase.js"></script>

    <script src="./static/js/components/auth.js"></script>

  </head>
  <body>
    <div id="auth">
      <auth-form></auth-form>
    </div>

    <script>
      new Vue({ el: '#auth' })
    </script>
  </body>
</html>
