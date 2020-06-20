<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>questionnaire</title>

    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="./static/js/auth.js"></script>
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
