const router = new VueRouter({
  mode: "hash",
  // base:'/app/public/',
  routes: [
    {
      path: "/auth",
      component: AuthPage,
    },
    {
      path: "/questionnaire/:id",
      component: QuestionnairePage,
    },
    {
      path: "/result",
      component: ResultPage,
    },
    {
      path: "/add_questionnaire",
      component: AddQuestionnaire,
    },
  ],
});
