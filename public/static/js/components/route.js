const router = new VueRouter({
  mode: "hash",
  // base:'/app/public/',
  routes: [
    {
      path: "/auth",
      component: AuthPage,
    },
    {
      path: "/questionnaire",
      component: QuestionnairePage,
    },
    {
      path: "/result",
      component: ResultPage,
    },
  ],
});
