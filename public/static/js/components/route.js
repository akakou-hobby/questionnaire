const router = new VueRouter({
  mode: "hash",
  // base:'/app/public/',
  routes: [
    {
      path: "/questionnaires/new",
      component: CreateQuestionnairePage,
    },
    {
      path: "/questionnaires/:id/",
      component: ShowAnswersPage,
    },
    {
      path: "/questionnaires/:id/new",
      component: CreateAnswerPage,
    },
  ],
});
